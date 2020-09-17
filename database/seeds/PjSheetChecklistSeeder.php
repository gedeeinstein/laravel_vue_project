<?php
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\PjChecklist;
// --------------------------------------------------------------------------
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjSheetChecklistSeeder extends Seeder {
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
                // Sheet Checklist
                // ----------------------------------------------------------
                $append = (object) array( 'pj_sheet_id' => $sheet->id );
                $append->driveway = $faker->boolean();
                // ----------------------------------------------------------
                if( $project->overall_area <= 1000 ){
                    // ------------------------------------------------------
                    $append->water_draw_count = $faker->numberBetween( 100, 9000 );
                    $append->new_road_type = $faker->numberBetween( 1, 3 );
                    // ------------------------------------------------------
                    if( 1 === $append->new_road_type || 2 === $append->new_road_type ){
                        $append->new_road_width = $faker->numberBetween( 6, 12 );
                        $append->new_road_length = $faker->numberBetween( 100, 200 );
                    }
                    // ------------------------------------------------------
                    $append->side_groove = $faker->numberBetween( 1, 3 );
                    // ------------------------------------------------------
                    if( 1 === $append->side_groove || 2 === $append->side_groove ){
                        $append->side_groove_length = $faker->numberBetween( 5, 20 );
                        $append->fill = $faker->numberBetween( 5, 20 );
                        $append->no_fill = $faker->boolean();
                    }
                    // ------------------------------------------------------
                    $append->retaining_wall = $faker->numberBetween( 1, 2 );
                    // ------------------------------------------------------
                    if( 1 === $append->retaining_wall ){
                        $append->retaining_wall_height = $faker->numberBetween( 1, 7 );
                        $append->retaining_wall_length = $faker->numberBetween( 4, 8 );
                    }
                    // ------------------------------------------------------
                    $append->development_cost = $faker->numberBetween( 1, 4 );
                    $append->main_pipe_is_distant = $faker->boolean();
                    // ------------------------------------------------------
                    if( $append->driveway ){
                        $append->road_sharing = $faker->numberBetween( 1, 2 );
                        $append->traffic_excavation_consent = $faker->numberBetween( 1, 2 );
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
                factory( PjChecklist::class )->create( (array) $append );
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------