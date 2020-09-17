<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MasLotBuilding;
use Faker\Generator as Faker;

$factory->define(MasLotBuilding::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->state(MasLotBuilding::class, 'init', [
    'id' => null,
    'pj_lot_building_a_id' => null,
    'sale_lot_building_id' => null,
    'exists_land_residential' => 1,
    'parcel_city' => null,
    'parcel_city_extra' => null,
    'parcel_town' => null,
    'parcel_number_first' => null,
    'parcel_number_second' => null,
    'building_number_first' => null,
    'building_number_second' => null,
    'building_number_third' => null,
    'building_usetype' => null,
    'building_attached' => null,
    'building_attached_select' => null,
    'building_date_nengou' => null,
    'building_date_year' => null,
    'building_date_month' => null,
    'building_date_day' => null,
    'building_structure' => null,
    'building_floor_count' => null,
    'building_roof' => null,
    'building_type' => rand(1, 2),
]);
