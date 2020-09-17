<?php
// --------------------------------------------------------------------------
use App\Models\Project;
// --------------------------------------------------------------------------
use App\Models\PjStock;
use App\Models\PjStockTax;
use App\Models\PjStockOther;
use App\Models\PjStockSurvey;
use App\Models\PjStockFinance;
use App\Models\PjStockRegister;
use App\Models\PjStockProcurement;
use App\Models\PjStockConstruction;
// --------------------------------------------------------------------------
use App\Models\PjStockCost;
use App\Models\PjStockCostRow;
// --------------------------------------------------------------------------
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjSheetStockSeeder extends Seeder {
    // ----------------------------------------------------------------------
    public function run(){
        // ------------------------------------------------------------------
        $faker = Faker::create('ja_JP');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $projects = Project::with('sheets')->get();
        $projects->each( function( $project ) use( $faker ){
            // --------------------------------------------------------------
            if( !empty( $project->sheets )) $project->sheets->each( function( $sheet ) use( $project, $faker ){
                // ----------------------------------------------------------
                // Sheet stockings / purchasing
                // ----------------------------------------------------------
                $append = array( 'pj_sheet_id' => $sheet->id );
                factory( PjStock::class, 1 )->create( $append )->each( function( $stock ) use( $faker ){
                    // ------------------------------------------------------
                    $total = 0;
                    // ------------------------------------------------------

                    
                    // ------------------------------------------------------
                    // Stock procurement
                    // ------------------------------------------------------
                    $append = array( 'pj_stocking_id' => $stock->id );
                    $procurement = factory( PjStockProcurement::class, 1 )->create( $append );
                    // ------------------------------------------------------
                    // Total calculation
                    // ------------------------------------------------------
                    if( !empty( $procurement->brokerage_fee )) $total += $procurement->brokerage_fee;
                    // ------------------------------------------------------


                    // ------------------------------------------------------
                    // Stock register
                    // ------------------------------------------------------
                    $parent = factory( PjStockCost::class )->create([ 'type' => 'register' ]);
                    $append = array( 'pj_stocking_id' => $stock->id, 'pj_stock_cost_parent_id' => $parent->id );
                    $register = factory( PjStockRegister::class )->create( $append );
                    // ------------------------------------------------------
                    // Total calculation
                    // ------------------------------------------------------
                    if( !empty( $register->loss )) $total += $register->loss;
                    if( !empty( $register->mortgage_setting )) $total += $register->mortgage_setting;
                    if( !empty( $register->fixed_assets_tax )) $total += $register->fixed_assets_tax;
                    if( !empty( $register->transfer_of_ownership )) $total += $register->transfer_of_ownership;
                    // ------------------------------------------------------
                    // Generate dynamic parent rows
                    // ------------------------------------------------------
                    $amount = $faker->numberBetween( 0, 3 );
                    $append = array( 'pj_stock_cost_parent_id' => $parent->id );
                    if( $amount ){
                        // --------------------------------------------------
                        $rows = factory( PjStockCostRow::class, $amount )->create( $append );
                        $rows->each( function( $row ) use( $total ){
                            if( !empty( $row->value )) $total += $row->value;
                        });
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------


                    // ------------------------------------------------------
                    // Stock finance
                    // ------------------------------------------------------
                    $parent = factory( PjStockCost::class )->create([ 'type' => 'finance' ]);
                    $append = array( 'pj_stocking_id' => $stock->id, 'pj_stock_cost_parent_id' => $parent->id );
                    $finance = factory( PjStockFinance::class )->create( $append );
                    // ------------------------------------------------------
                    // Total calculation
                    // ------------------------------------------------------
                    if( !empty( $finance->stamp )) $total += $finance->stamp;
                    if( !empty( $finance->banking_fee )) $total += $finance->banking_fee;
                    if( !empty( $finance->total_interest_rate )) $total += $finance->total_interest_rate;
                    if( !empty( $finance->expected_interest_rate )) $total += $finance->expected_interest_rate;
                    // ------------------------------------------------------
                    // Generate dynamic parent rows
                    // ------------------------------------------------------
                    $amount = $faker->numberBetween( 0, 3 );
                    $append = array( 'pj_stock_cost_parent_id' => $parent->id );
                    if( $amount ){
                        // --------------------------------------------------
                        $rows = factory( PjStockCostRow::class, $amount )->create( $append );
                        $rows->each( function( $row ) use( $total ){
                            if( !empty( $row->value )) $total += $row->value;
                        });
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------


                    // ------------------------------------------------------
                    // Stock tax
                    // ------------------------------------------------------
                    $parent = factory( PjStockCost::class )->create([ 'type' => 'tax' ]);
                    $append = array( 'pj_stocking_id' => $stock->id, 'pj_stock_cost_parent_id' => $parent->id );
                    $tax = factory( PjStockTax::class )->create( $append );
                    // ------------------------------------------------------
                    // Total calculation
                    // ------------------------------------------------------
                    if( !empty( $tax->property_acquisition_tax )) $total += $tax->property_acquisition_tax;
                    if( !empty( $tax->the_following_year_the_city_tax )) $total += $tax->the_following_year_the_city_tax;
                    // ------------------------------------------------------
                    // Generate dynamic parent rows
                    // ------------------------------------------------------
                    $amount = $faker->numberBetween( 0, 3 );
                    $append = array( 'pj_stock_cost_parent_id' => $parent->id );
                    if( $amount ){
                        // --------------------------------------------------
                        $rows = factory( PjStockCostRow::class, $amount )->create( $append );
                        $rows->each( function( $row ) use( $total ){
                            if( !empty( $row->value )) $total += $row->value;
                        });
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------


                    // ------------------------------------------------------
                    // Stock construction
                    // ------------------------------------------------------
                    $parent = factory( PjStockCost::class )->create([ 'type' => 'construction' ]);
                    $append = array( 'pj_stocking_id' => $stock->id, 'pj_stock_cost_parent_id' => $parent->id );
                    $construction = factory( PjStockConstruction::class )->create( $append );
                    // ------------------------------------------------------
                    // Total calculation
                    // ------------------------------------------------------
                    if( !empty( $construction->building_demolition )) $total += $construction->building_demolition;
                    if( !empty( $construction->retaining_wall_demolition )) $total += $construction->retaining_wall_demolition;
                    if( !empty( $construction->transfer_electric_pole )) $total += $construction->transfer_electric_pole;
                    if( !empty( $construction->waterwork_construction )) $total += $construction->waterwork_construction;
                    if( !empty( $construction->fill_work )) $total += $construction->fill_work;
                    if( !empty( $construction->retaining_wall_construction )) $total += $construction->retaining_wall_construction;
                    if( !empty( $construction->road_construction )) $total += $construction->road_construction;
                    if( !empty( $construction->side_groove_construction )) $total += $construction->side_groove_construction;
                    if( !empty( $construction->construction_work_set )) $total += $construction->construction_work_set;
                    if( !empty( $construction->location_designation_application_fee )) $total += $construction->location_designation_application_fee;
                    if( !empty( $construction->development_commissions_fee )) $total += $construction->development_commissions_fee;
                    if( !empty( $construction->cultural_property_research_fee )) $total += $construction->cultural_property_research_fee;
                    // ------------------------------------------------------
                    // Generate dynamic parent rows
                    // ------------------------------------------------------
                    $amount = $faker->numberBetween( 0, 3 );
                    $append = array( 'pj_stock_cost_parent_id' => $parent->id );
                    if( $amount ){
                        // --------------------------------------------------
                        $rows = factory( PjStockCostRow::class, $amount )->create( $append );
                        $rows->each( function( $row ) use( $total ){
                            if( !empty( $row->value )) $total += $row->value;
                        });
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------


                    // ------------------------------------------------------
                    // Stock survey
                    // ------------------------------------------------------
                    $parent = factory( PjStockCost::class )->create([ 'type' => 'survey' ]);
                    $append = array( 'pj_stocking_id' => $stock->id, 'pj_stock_cost_parent_id' => $parent->id );
                    $survey = factory( PjStockSurvey::class )->create( $append );
                    // ------------------------------------------------------
                    // Total calculation
                    // ------------------------------------------------------
                    if( !empty( $survey->fixed_survey )) $total += $survey->fixed_survey;
                    if( !empty( $survey->divisional_registration )) $total += $survey->divisional_registration;
                    if( !empty( $survey->boundary_pile_restoration )) $total += $survey->boundary_pile_restoration;
                    // ------------------------------------------------------
                    // Generate dynamic parent rows
                    // ------------------------------------------------------
                    $amount = $faker->numberBetween( 0, 3 );
                    $append = array( 'pj_stock_cost_parent_id' => $parent->id );
                    if( $amount ){
                        // --------------------------------------------------
                        $rows = factory( PjStockCostRow::class, $amount )->create( $append );
                        $rows->each( function( $row ) use( $total ){
                            if( !empty( $row->value )) $total += $row->value;
                        });
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------


                    // ------------------------------------------------------
                    // Stock other
                    // ------------------------------------------------------
                    $parent = factory( PjStockCost::class )->create([ 'type' => 'other' ]);
                    $append = array( 'pj_stocking_id' => $stock->id, 'pj_stock_cost_parent_id' => $parent->id );
                    $other = factory( PjStockOther::class )->create( $append );
                    // ------------------------------------------------------
                    // Total calculation
                    // ------------------------------------------------------
                    if( !empty( $other->referral_fee )) $total += $other->referral_fee;
                    if( !empty( $other->eviction_fee )) $total += $other->eviction_fee;
                    if( !empty( $other->water_supply_subscription )) $total += $other->water_supply_subscription;
                    // ------------------------------------------------------
                    // Generate dynamic parent rows
                    // ------------------------------------------------------
                    $amount = $faker->numberBetween( 0, 3 );
                    $append = array( 'pj_stock_cost_parent_id' => $parent->id );
                    if( $amount ){
                        // --------------------------------------------------
                        $rows = factory( PjStockCostRow::class, $amount )->create( $append );
                        $rows->each( function( $row ) use( $total ){
                            if( !empty( $row->value )) $total += $row->value;
                        });
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------


                    // ------------------------------------------------------
                    // Total update
                    // ------------------------------------------------------
                    $stock->total_budget_cost = $total;
                    $stock->total_decision_cost = $total;
                    $stock->save();
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