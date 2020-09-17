<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SaleContractDeposit as Deposit;
use Faker\Factory as Faker;

$factory->define(Deposit::class, function () {

    $faker = Faker::create('ja_JP');

    return [
        'sale_contract_id' => null,
        'price'     => $faker->numberBetween(2000, 5000),
        'date'      => $faker->dateTimeThisYear($max = 'now', $timezone = null),
        'status'    => $faker->numberBetween(1, 3),
        'account'   => $faker->numberBetween(1, 10),
        'note'      => $faker->sentence($nbWords = 6, $variableNbWords = true),
    ];
});

$factory->state(Deposit::class, 'init', [
    'id'        => null,
    'sale_contract_id' => null,
    'price'     => 0,
    'date'      => null,
    'status'    => null,
    'account'   => null,
    'note'      => null,
]);
