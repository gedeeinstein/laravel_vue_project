<?php
// --------------------------------------------------------------------------

use App\Models\OtherAdditionalQaCategory;
use App\Models\OtherAdditionalQaCheck;
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\PjAdditionalQa;
// --------------------------------------------------------------------------
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjAdditionalQaSeeder extends Seeder {
    // ----------------------------------------------------------------------
    public function run(){
        // ------------------------------------------------------------------
        Project::all()->each( function( $project ){
            // --------------------------------------------------------------
            $faker = Faker::create('ja_JP');
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create Additional Q&A answers
            // --------------------------------------------------------------
            $categories = OtherAdditionalQaCategory::with('questions')->get();
            $categories->each( function( $category ) use( $project, $faker ){
                // ----------------------------------------------------------
                if( isset( $category->questions ) && !empty( $category->questions )){
                    $category->questions->each( function( $question ) use( $project, $faker ){
                        // --------------------------------------------------
                        if( $question->input_type ){
                            // ----------------------------------------------
                            $answers = $faker->sentence();
                            $type = $question->input_type;
                            $options = json_decode( $question->choices );
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // If question has choices
                            // ----------------------------------------------
                            if( !empty( $options )){
                                // ------------------------------------------
                                // If a radio button type, choose one random option
                                // ------------------------------------------
                                if( 1 === $type && !empty( $options )){
                                    $answers = $options[ array_rand( $options )];
                                }
                                // ------------------------------------------
                                // If selectbox type, choose random options and return as array
                                // ------------------------------------------
                                elseif( 2 === $type && !empty( $options )){
                                    // --------------------------------------
                                    $answers = array();
                                    foreach( $options as $opt ){
                                        $include = $faker->boolean(70);
                                        if( $include ) $answers[] = $opt;
                                    }
                                    // --------------------------------------
                                    $answers = json_encode( $answers, JSON_UNESCAPED_UNICODE );
                                    // --------------------------------------
                                }
                            }
                            // ----------------------------------------------

                            // ----------------------------------------------
                            // Create the answer
                            // ----------------------------------------------
                            factory( PjAdditionalQa::class )->create([
                                'answer' => $answers,
                                'project_id' => $project->id,
                                'question_id' => $question->id
                            ]);
                            // ----------------------------------------------
                        }
                        // --------------------------------------------------
                    });
                }
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------