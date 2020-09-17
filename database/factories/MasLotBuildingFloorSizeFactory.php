<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MasLotBuildingFloorSize;
use Faker\Generator as Faker;

$factory->define(MasLotBuildingFloorSize::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->state(MasLotBuildingFloorSize::class, 'init', [
    'id' => null,
    'mas_lot_building_id' => null,
    'floor_size' => null,
]);
