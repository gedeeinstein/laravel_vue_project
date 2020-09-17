<?php
// -----------------------------------------------------------------------------
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// -----------------------------------------------------------------------------
use App\Models\PjProperty;
use Faker\Generator as Faker;
// -----------------------------------------------------------------------------

$factory->define(PjProperty::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->state(PjProperty::class, 'init', [
    'id'                            =>  null,
    'school_primary'                =>  null,
    'school_primary_distance'       =>  null,
    'school_juniorhigh'             =>  null,
    'school_juniorhigh_distance'    =>  null,
    'registry_size'                 =>  0,
    'registry_size_status'          =>  null,
    'survey_size'                   =>  0,
    'survey_size_status'            =>  null,
    'fixed_asset_tax_route_value'   =>  null,
]);
