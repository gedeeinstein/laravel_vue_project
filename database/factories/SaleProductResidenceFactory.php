<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SaleProductResidence;
use Faker\Factory as Faker;
use Carbon\Carbon;

$factory->define(SaleProductResidence::class, function () {

    $faker = Faker::create('ja_JP');

    return [
        'sale_contract_id'      => null,
        'mas_lot_building_id'   => null,
        'type'                  => $faker->numberBetween(1, 2),
        'receipt'               => $faker->numberBetween(1, 3),
        'receipt_date'          => $faker->dateTimeBetween($startDate = now(), $endDate = $startDate->addDays(10), $timezone = null),
    ];
});

$factory->state(SaleProductResidence::class, 'init', [
    'id'                    => null,
    'sale_contract_id'      => null,
    'mas_lot_building_id'   => null,
    'type'                  => null,
    'receipt'               => null,
    'receipt_date'          => null,
]);
