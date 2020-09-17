<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MasLotRoad;
use Faker\Generator as Faker;

$factory->define(MasLotRoad::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->state(MasLotRoad::class, 'init', [
    'id' => null,
    'pj_lot_road_a_id' => null,
    'sale_lot_road_id' => null,
    'exists_road_residential' => 1,
    'parcel_city' => null,
    'parcel_city_extra' => null,
    'parcel_town' => null,
    'parcel_number_first' => null,
    'parcel_number_second' => null,
    'parcel_land_category' => null,
    'fire_protection' => null,
    'fire_protection_same' => null,
    'cultural_property_reserves' => null,
    'cultural_property_reserves_same' => null,
    'cultural_property_reserves_name' => null,
    'district_planning' => null,
    'district_planning_same' => null,
    'district_planning_name' => null,
    'scenic_area_same' => null,
    'landslide' => null,
    'landslide_same' => null,
    'residential_land_development' => null,
    'residential_land_development_same' => null,
    'urbanization_area' => null,
    'urbanization_area_sub' => null,
    'urbanization_area_number' => null,
    'urbanization_area_date' => null,
    'urbanization_area_same' => null,
    'project_current_situation' => null,
    'project_current_situation_other' => null,
    'project_current_situation_same_to_basic' => 1,
    'project_set_back' => null,
    'project_set_back_same_to_basic' => 1,
    'project_private_road' => null,
    'project_private_road_same_to_basic' => 1,
]);
