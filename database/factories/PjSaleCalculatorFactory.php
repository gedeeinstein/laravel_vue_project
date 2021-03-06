<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\PjSaleCalculator as Calculator;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( Calculator::class, function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $faker = Faker::create('ja_JP');
    // ----------------------------------------------------------------------
    
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->pj_sale_id = 1;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $result->sales_divisions = $faker->numberBetween( 10, 999 );
    $result->unit_average_area = $faker->numberBetween( 100, 1900 );
    $result->rate_of_return = $faker->numberBetween( 10, 90 );
    $result->sales_unit_price = $faker->numberBetween( 1000000, 90000000 );
    $result->unit_average_price = $faker->numberBetween( 1000000, 90000000 );
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
$factory->state( Calculator::class, 'init', function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->pj_sale_id = null;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $result->sales_divisions = null;
    $result->unit_average_area = null;
    $result->rate_of_return = null;
    $result->sales_unit_price = null;
    $result->unit_average_price = null;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $result->created_at = null;
    $result->updated_at = null;
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
