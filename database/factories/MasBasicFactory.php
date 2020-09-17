<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MasBasic;
use Faker\Generator as Faker;

$factory->define(MasBasic::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->state(MasBasic::class, 'init', [
    'id' => null,
    'project_id' => null,
    'transportation' => null,
    'transportation_station' => null,
    'transportation_distance' => null,
    'transportation_time' => null,
    'height_district' => null,
    'height_district_use' => null,
    'school_primary' => null,
    'school_primary_distance' => null,
    'school_juniorhigh' => null,
    'school_juniorhigh_distance' => null,
    'basic_parcel_build_ratio' => null,
    'basic_parcel_floor_ratio' => null,
    'basic_fire_protection' => null,
    'basic_cultural_property_reserves' => null,
    'basic_cultural_property_reserves_name' => null,
    'basic_district_planning' => null,
    'basic_district_planning_name' => null,
    'basic_scenic_area' => null,
    'basic_landslide' => null,
    'basic_residential_land_development' => null,
    'project_urbanization_area' => null,
    'project_urbanization_area_status' => null,
    'project_urbanization_area_sub' => null,
    'project_urbanization_area_date' => null,
    'project_current_situation' => null,
    'project_current_situation_other' => null,
    'project_set_back' => null,
    'project_private_road' => null,
    'status' => null,
]);
