<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\PjStockRegister::class, function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $faker = Faker::create('ja_JP');
    // ----------------------------------------------------------------------
    $result->pj_stocking_id = 1;
    $result->pj_stock_cost_parent_id = 1;
    // ----------------------------------------------------------------------
    $result->transfer_of_ownership = $faker->numberBetween( 100000, 1000000 );
    $result->transfer_of_ownership_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->mortgage_setting = $faker->numberBetween( 100000, 1000000 );
    $result->mortgage_setting_plan = $faker->numberBetween( 100000, 1000000 );
    // ----------------------------------------------------------------------
    $result->fixed_assets_tax = $faker->numberBetween( 100, 5000 );
    $result->fixed_assets_tax_date = Carbon::now();
    // ----------------------------------------------------------------------
    $result->loss = 15000;
    $result->loss_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
$factory->state( App\Models\PjStockRegister::class, 'init', function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    // ----------------------------------------------------------------------
    $result->pj_stocking_id = null;
    $result->pj_stock_cost_parent_id = null;
    // ----------------------------------------------------------------------
    $result->transfer_of_ownership = null;
    $result->transfer_of_ownership_memo = null;
    // ----------------------------------------------------------------------
    $result->mortgage_setting = null;
    $result->mortgage_setting_plan = null;
    // ----------------------------------------------------------------------
    $result->fixed_assets_tax = null;
    $result->fixed_assets_tax_date = null;
    // ----------------------------------------------------------------------
    $result->loss = null;
    $result->loss_memo = null;
    // ----------------------------------------------------------------------
    $result->created_at = null;
    $result->updated_at = null;
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
