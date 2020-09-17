<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\SaleFeePrice::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('ja_JP');
    $data = new \stdClass;

    $data->price   = $faker->numberBetween(2000, 5000);
    $data->status  = $faker->numberBetween(1, 3);
    $data->date    = $faker->dateTimeThisYear($max = 'now', $timezone = null);
    $data->account = $faker->numberBetween(1, 10);

    $data->sale_contract_id = 1;

    $data->created_at = Carbon::now();
    $data->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------

$factory->state(App\Models\SaleFeePrice::class, 'init', function(){
    $data = new \stdClass;
    // -------------------------------------------------------------------------
    $data->id      = null;
    $data->price   = 0;
    $data->status  = null;
    $data->date    = null;
    $data->account = null;
    $data->sale_contract_id = 1;
    $data->sale_fee_id = 1;
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
