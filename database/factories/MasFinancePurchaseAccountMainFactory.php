<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\MasFinancePurchaseAccountMain::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('ja_JP');
    $data = new \stdClass;

    $data->account_main = $faker->unique()->numberBetween(1, 10);
    $data->mas_finance_id = 1;

    $data->created_at = Carbon::now();
    $data->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
