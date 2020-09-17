<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\PjStockSurvey::class, function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $faker = Faker::create('ja_JP');
    // ----------------------------------------------------------------------
    $result->pj_stocking_id = 1;
    $result->pj_stock_cost_parent_id = 1;
    // ----------------------------------------------------------------------
    $result->fixed_survey = $faker->numberBetween( 100000, 1000000 );
    $result->fixed_survey_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->divisional_registration = $faker->numberBetween( 100000, 1000000 );
    $result->divisional_registration_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->boundary_pile_restoration = $faker->numberBetween( 100000, 1000000 );
    $result->boundary_pile_restoration_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
$factory->state( App\Models\PjStockSurvey::class, 'init', function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    // ----------------------------------------------------------------------
    $result->pj_stocking_id = null;
    $result->pj_stock_cost_parent_id = null;
    // ----------------------------------------------------------------------
    $result->fixed_survey = null;
    $result->fixed_survey_memo = null;
    // ----------------------------------------------------------------------
    $result->divisional_registration = null;
    $result->divisional_registration_memo = null;
    // ----------------------------------------------------------------------
    $result->boundary_pile_restoration = null;
    $result->boundary_pile_restoration_memo = null;
    // ----------------------------------------------------------------------
    $result->created_at = null;
    $result->updated_at = null;
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
