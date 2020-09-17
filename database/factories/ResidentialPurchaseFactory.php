<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PjLotResidentialPurchase;
use Faker\Generator as Faker;

$factory->define(PjLotResidentialPurchase::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->state(PjLotResidentialPurchase::class, 'init', [
    'id'                        => null,
    'pj_lot_residential_a_id'   => null,
    'urbanization_area'         => 0,
    'urbanization_area_sub'     => 0,
    'urbanization_area_number'  => null,
    'urbanization_area_date'    => null,
    'urbanization_area_same'    => 1,
]);
