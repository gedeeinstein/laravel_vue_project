<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\SaleFee::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('ja_JP');
    $data = new \stdClass;

    $data->enable          = $faker->numberBetween(1, 2);
    $data->customer        = $faker->numerify('Customer ##');
    $data->note            = $faker->numerify('Note ##');
    $data->balance         = $faker->numberBetween(1, 4);
    $data->receipt_company = $faker->numberBetween(1, 10);

    $data->sale_contract_id = 1;

    $data->created_at = Carbon::now();
    $data->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------

$factory->state(App\Models\SaleFee::class, 'init', function(){
    $data = new \stdClass;
    // -------------------------------------------------------------------------
    $data->id               = null;
    $data->enable           = 1;
    $data->customer         = null;
    $data->note             = null;
    $data->balance          = null;
    $data->receipt_company  = null;
    $data->sale_contract_id = null;
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
