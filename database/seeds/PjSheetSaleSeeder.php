<?php
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\PjSale as Sale;
use App\Models\PjSalePlan as Plan;
use App\Models\PjSalePlanSection as Section;
use App\Models\PjSaleCalculator as Calculator;
// --------------------------------------------------------------------------
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjSheetSaleSeeder extends Seeder {
    // ----------------------------------------------------------------------
    public function run(){
        // ------------------------------------------------------------------
        $faker = Faker::create('ja_JP');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $projects = Project::with([ 'sheets', 'sheets.checklist', 'sheets.stock' ])->get();
        $projects->each( function( $project ) use( $faker ){
            // --------------------------------------------------------------
            if( !empty( $project->sheets )) $project->sheets->each( function( $sheet ) use( $project, $faker ){
                // ----------------------------------------------------------
                $stock = (object) $sheet->stock;
                $checklist = (object) $sheet->checklist;
                // ----------------------------------------------------------
                $sale = factory( Sale::class )->create([ 'pj_sheet_id' => $sheet->id ]);
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Sale calculators
                // ----------------------------------------------------------
                $count = 2;
                $tsubo_area = to_tsubo( $checklist->effective_area );
                $calculators = factory( Calculator::class, $count )->make();
                // ----------------------------------------------------------
                foreach( $calculators as $entry ){
                    // ------------------------------------------------------
                    $entry->pj_sale_id = $sale->id;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Unit average area calculation
                    // https://bit.ly/3e8ssLn
                    // ------------------------------------------------------
                    $entry->sales_divisions = $faker->numberBetween( 1000, 99999 );
                    $entry->unit_average_area = round( $tsubo_area / $entry->sales_divisions, 2 );
                    // ------------------------------------------------------
    
                    // ------------------------------------------------------
                    // Sale unit price calculation
                    // [S34-1:坪単価] / ( 1.00 - S31-2 / 100 ) / S31-1 (round down)
                    // https://bit.ly/3aTUxUG
                    // ------------------------------------------------------
                    $total = floor( $stock->total_budget_cost / $checklist->effective_area );
                    $entry->sales_unit_price = floor( $total / ( 1.00 - $entry->rate_of_return / 100 ) / $entry->sales_divisions );
                    // ------------------------------------------------------
    
                    // ------------------------------------------------------
                    // Sale unit average unit
                    // [S34-1] / ( 1.00 - S31-2 / 100 ) / S31-1 (round down)
                    // https://bit.ly/34qjqor
                    // ------------------------------------------------------
                    $total = $stock->total_budget_cost;
                    $entry->unit_average_price = floor( $total / ( 1.00 - $entry->rate_of_return / 100 ) / $entry->sales_divisions );
                    // ------------------------------------------------------
    
                    // ------------------------------------------------------
                    $entry->save(); // Save to database
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------


                // ----------------------------------------------------------
                // Sale plans
                // ----------------------------------------------------------
                $amount = $faker->numberBetween( 3, 5 );
                $default = array( 'pj_sale_id' => $sale->id );
                $plans = factory( Plan::class, $amount )->create( $default );
                // ----------------------------------------------------------
                $plans->each( function( $plan, $index ) use( $faker, $stock, $tsubo_area ){
                    // ------------------------------------------------------
                    $planIndex = $index +1;
                    $plan->tab_index = $planIndex;
                    $plan->plan_name = "Plan {$planIndex}";
                    // ------------------------------------------------------
                    $plan->export = !$index; // True only for the first plan
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Plan sections
                    // ------------------------------------------------------
                    $total = new \stdClass;
                    // ------------------------------------------------------
                    $amount = $faker->numberBetween( 2, 6 );
                    $default = array( 'pj_sale_plan_id' => $plan->id );
                    $sections = factory( Section::class, $amount )->create( $default );
                    // ------------------------------------------------------
                    $sections->each( function( $section, $index ) use( $amount, $tsubo_area, $total ){
                        // --------------------------------------------------
                        $sectionIndex = $index +1;
                        $section->divisions_number = $sectionIndex;
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Section reference area calculation
                        // to_tsubo( checklist.effective_area ) / section count
                        // https://bit.ly/2XwfkJY
                        // --------------------------------------------------
                        $area = $tsubo_area / $amount;
                        $section->reference_area = floor( $area * 10000 ) / 10000;
                        $section->planned_area = $section->reference_area;
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Section unit price calculation
                        // S32-2 / S32-1 (round down)
                        // floor( section.unit_selling_price / section.planned_area )
                        // https://bit.ly/3aZrgIi
                        // --------------------------------------------------
                        $section->unit_price = floor( $section->unit_selling_price / $section->planned_area );
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Section brokerage fee
                        // (( S32-2 * 0.03 ) ＋60000 ) * 1.08 (round down)
                        // https://bit.ly/3aZrgIi
                        // --------------------------------------------------
                        $base = $section->unit_selling_price * 0.03;
                        $section->brokerage_fee = floor(( $base + 60000 ) * 1.08 );
                        // --------------------------------------------------
                        if( 2 === $section->brokerage_fee_type ){
                            $section->brokerage_fee = -1 * abs( $section->brokerage_fee );
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        // Total calculation
                        // --------------------------------------------------
                        if( empty( $total->reference )) $total->reference = $section->reference_area;
                        else $total->reference += $section->reference_area;
                        // --------------------------------------------------
                        if( empty( $total->planned )) $total->planned = $section->planned_area;
                        else $total->planned += $section->planned_area;
                        // --------------------------------------------------
                        if( empty( $total->price )) $total->price = $section->unit_selling_price;
                        else {
                            // ----------------------------------------------
                            // Brokerage fee type 
                            // https://bit.ly/3aZrgIi
                            // ----------------------------------------------
                            $selling_price = $section->unit_selling_price;
                            if( 2 === $section->brokerage_fee_type ){
                                $selling_price = -1 * $selling_price;
                            }
                            // ----------------------------------------------
                            $total->price += $section->unit_selling_price;
                            // ----------------------------------------------
                        }
                        // --------------------------------------------------

                        // --------------------------------------------------
                        $section->save(); // Save section updates
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------


                    // ------------------------------------------------------
                    // Total updates
                    // ------------------------------------------------------
                    if( !empty( $total->reference )) $plan->reference_area_total = $total->reference;
                    if( !empty( $total->planned )) $plan->planned_area_total = $total->planned;
                    if( !empty( $total->price )) $plan->unit_selling_price_total = $total->price;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Gross profit plan
                    // [S32-13] - [S34-1] 円 ( [S33-1] / [S32-13] × 100% )
                    // https://bit.ly/3e8UBC0
                    // ------------------------------------------------------
                    $total = $stock->total_budget_cost;
                    $price = $plan->unit_selling_price_total;
                    $plan->gross_profit_plan = $price - $total;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Gross profit total
                    // [S33-1] + sum([S32-3])
                    // * sum([S32-3]):
                    // - If [S32-4] =「収」: plus 
                    // - If [S32-4] =「支」: minus
                    // - If [S32-4] =「無」: zero  
                    // ( S33-2 / S32-13×100 % )
                    // https://bit.ly/2JRktnV
                    // ------------------------------------------------------
                    $profit = $plan->gross_profit_plan; // S33-1
                    $sum = $sections->sum( function( $section ){ // sum of S32-3
                        // --------------------------------------------------
                        // Brokerage Type - 1: 収 2: 支 3: 無
                        // --------------------------------------------------
                        $fee = $section->brokerage_fee;
                        $type = $section->brokerage_fee_type;
                        // --------------------------------------------------
                        if( 2 === $type ) $fee = -1 * $fee;
                        elseif( 3 === $type ) $fee = 0;
                        // --------------------------------------------------
                        return $fee;
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------
                    $plan->gross_profit_total_plan = $profit + $sum;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    $plan->save(); // Save plan updates
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------