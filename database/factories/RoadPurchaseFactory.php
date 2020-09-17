<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PjLotRoadPurchase;
use Faker\Generator as Faker;

$factory->define(PjLotRoadPurchase::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->state(PjLotRoadPurchase::class, 'init', [
    'id'                        => null,
    'pj_lot_road_a_id'          => null,
    'urbanization_area'         => 0,
    'urbanization_area_sub'     => 0,
    'urbanization_area_number'  => null,
    'urbanization_area_date'    => null,
    'urbanization_area_same'    => 1,
]);
