<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\PjStockTax::class, function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $faker = Faker::create('ja_JP');
    // ----------------------------------------------------------------------
    $result->pj_stocking_id = 1;
    $result->pj_stock_cost_parent_id = 1;
    // ----------------------------------------------------------------------
    $result->property_acquisition_tax = $faker->numberBetween( 100000, 1000000 );
    $result->property_acquisition_tax_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->the_following_year_the_city_tax = $faker->numberBetween( 100000, 1000000 );
    $result->the_following_year_the_city_tax_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
$factory->state( App\Models\PjStockTax::class, 'init', function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    // ----------------------------------------------------------------------
    $result->pj_stocking_id = null;
    $result->pj_stock_cost_parent_id = null;
    // ----------------------------------------------------------------------
    $result->property_acquisition_tax = null;
    $result->property_acquisition_tax_memo = null;
    // ----------------------------------------------------------------------
    $result->the_following_year_the_city_tax = null;
    $result->the_following_year_the_city_tax_memo = null;
    // ----------------------------------------------------------------------
    $result->created_at = null;
    $result->updated_at = null;
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
