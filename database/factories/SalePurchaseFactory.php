<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SalePurchase;
use Faker\Factory as Faker;
use Carbon\Carbon;

$factory->define(SalePurchase::class, function () {
    $faker = Faker::create('ja_JP');
    $start_date = now();

    return [
        'sale_contract_id'              => null,
        'price'                         => $faker->randomFloat( 2, 100000, 200000),
        'price_memo'                    => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'contract_deposit'              => $faker->randomFloat( 2, 50000, 100000),
        'deposit_memo'                  => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'contract_date_request'         => $faker->dateTimeBetween($start_date, $end_date = $start_date->addDays(10), $timezone = null),
        'contract_date_request_memo'    => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'payment_date_request'          => $faker->dateTimeBetween($start_date = $end_date, $end_date = $start_date->addDays(10), $timezone = null),
        'payment_date_request_memo'     => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'outdoor_facility'              => $faker->numberBetween(1, 2),
        'outdoor_facility_memo'         => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'registration'                  => $faker->numberBetween(1, 3),
        'registration_memo'             => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'memo'                          => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'accept_result'                 => $faker->numberBetween(1, 3),
        'accept_result_memo'            => $faker->sentence($nbWords = 6, $variableNbWords = true),
    ];
});

$factory->state(SalePurchase::class, 'init', [
    'id'                            => null,
    'sale_contract_id'              => null,
    'price'                         => 0,
    'price_memo'                    => null,
    'contract_deposit'              => 0,
    'deposit_memo'                  => null,
    'contract_date_request'         => null,
    'contract_date_request_memo'    => null,
    'payment_date_request'          => null,
    'payment_date_request_memo'     => null,
    'outdoor_facility'              => null,
    'outdoor_facility_memo'         => null,
    'registration'                  => null,
    'registration_memo'             => null,
    'memo'                          => null,
    'accept_result'                 => null,
    'accept_result_memo'            => null,
]);
