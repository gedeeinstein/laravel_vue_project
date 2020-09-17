<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\PjProperty::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('en_US');
    $data = new \stdClass;

    $data->school_primary = '小学校';
    $data->school_primary_distance = $faker->numberBetween( 10, 100 );
    $data->school_juniorhigh = '中学校';
    $data->school_juniorhigh_distance = $faker->numberBetween( 10, 100 );
    $data->registry_size = '900';
    $data->registry_size_status = 2;
    $data->survey_size = '900';
    $data->survey_size_status = 1;
    $data->fixed_asset_tax_route_value = 0;

    $data->project_id = 1;

    $data->created_at = Carbon::now();
    $data->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
