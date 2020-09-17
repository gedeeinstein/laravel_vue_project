<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\PjStockOther::class, function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $faker = Faker::create('ja_JP');
    // ----------------------------------------------------------------------
    $result->pj_stocking_id = 1;
    $result->pj_stock_cost_parent_id = 1;
    // ----------------------------------------------------------------------
    $result->referral_fee = $faker->numberBetween( 100000, 1000000 );
    $result->referral_fee_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->eviction_fee = $faker->numberBetween( 100000, 1000000 );
    $result->eviction_fee_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->water_supply_subscription = $faker->numberBetween( 100000, 1000000 );
    $result->water_supply_subscription_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
$factory->state( App\Models\PjStockOther::class, 'init', function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    // ----------------------------------------------------------------------
    $result->pj_stocking_id = null;
    $result->pj_stock_cost_parent_id = null;
    // ----------------------------------------------------------------------
    $result->referral_fee = null;
    $result->referral_fee_memo = null;
    // ----------------------------------------------------------------------
    $result->eviction_fee = null;
    $result->eviction_fee_memo = null;
    // ----------------------------------------------------------------------
    $result->water_supply_subscription = null;
    $result->water_supply_subscription_memo = null;
    // ----------------------------------------------------------------------
    $result->created_at = null;
    $result->updated_at = null;
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
