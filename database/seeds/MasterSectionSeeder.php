<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use CrazyCodr\Converters\Roman;
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\MasSection as Section;
use App\Models\MasSectionPlan as Plan;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class MasterSectionSeeder extends Seeder {
    public function run(){
        DB::transaction( function(){
            // --------------------------------------------------------------
            $projects = Project::with('setting')->get();
            $projects->each( function( $project ){
                // ----------------------------------------------------------
                $roman = new Roman();
                $setting = $project->setting;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                $maxPlan = 3;
                $maxSection = 4;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Master section plans
                // ----------------------------------------------------------
                $planRange = range( 1, rand( 1, $maxPlan ));
                foreach( $planRange as $index => $number ){
                    // ------------------------------------------------------
                    $planIndex = $index +1;
                    $primary = $planIndex === count( $planRange );
                    $planNumber = strtolower( $roman->toRoman( $number ));
                    // ------------------------------------------------------
                    $plan = factory( Plan::class )->create([
                        'number'         => $number,
                        'project_id'     => $project->id,
                        'mas_setting_id' => $setting->id,
                        'plan_number'    => $planNumber,
                        'primary'        => $primary,
                    ]);
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Master sections
                    // ------------------------------------------------------
                    $sectionRange = range( 1, rand( 1, $maxSection ));
                    foreach( $sectionRange as $index => $section ){
                        $sectionIndex = $index +1;
                        factory( Section::class )->create([
                            'mas_section_plan_id' => $plan->id,
                            'project_id'          => $project->id,
                            'section_number'      => "{$planNumber}-{$sectionIndex}"
                        ]);
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            });
        // ------------------------------------------------------------------
        }, 5 );
    }
}
// --------------------------------------------------------------------------
