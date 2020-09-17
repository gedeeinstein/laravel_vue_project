<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PjPurchaseContractMediation;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(PjPurchaseContractMediation::class, function (Faker $faker) {

    $start_date = Carbon::now();

    return [
        'pj_purchase_contract_id'   => null,
        'dealtype'                  => $faker->numberBetween(1, 3),
        'balance'                   => $faker->numberBetween(1, 3),
        'reward'                    => $faker->numberBetween(3000, 5000),
        'date'                      => $faker->dateTimeBetween($start_date, $end_date = $start_date->addDays(10), $timezone = null),
        'status'                    => $faker->numberBetween(1, 2),
        'bank'                      => null,
        'trader_company_id'         => null,
        'trader_company_user'       => null,
    ];
});
