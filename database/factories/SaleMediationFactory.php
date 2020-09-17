<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\SaleMediation as Mediation;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( Mediation::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('ja_JP');
    $data = new \stdClass;

    $data->enable   = $faker->numberBetween(1, 2);
    $data->dealtype = $faker->numberBetween(1, 4);
    $data->balance  = $faker->numberBetween(1, 4);
    $data->reward   = $faker->numberBetween(2000, 5000);
    $data->date     = $faker->dateTimeThisYear($max = 'now', $timezone = null);
    $data->status   = $faker->numberBetween(1, 3);
    $data->bank     = $faker->numberBetween(1, 10);
    // $data->trader   = $faker->numerify('Trader ##');
    $data->trader   = $faker->numberBetween(1, 10);

    $data->sale_contract_id = 1;

    $data->created_at = Carbon::now();
    $data->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------

$factory->state(Mediation::class, 'init', [
    'id'                => null,
    'sale_contract_id'  => null,
    'enable'            => null,
    'dealtype'          => null,
    'balance'           => null,
    'reward'            => 0,
    'date'              => null,
    'status'            => null,
    'bank'              => null,
    'trader'            => null,
]);
