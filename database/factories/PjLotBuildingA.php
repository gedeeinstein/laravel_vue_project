<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\PjLotBuildingA::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create();
    $data = new \stdClass;

    $data->exists_building_residential = 1;
    $data->parcel_city = '-1';
    $data->parcel_city_extra = 'テスト';
    $data->parcel_town = 'テスト';
    $data->parcel_number_first = $faker->numberBetween( 1, 5 );
    $data->parcel_number_second = $faker->numberBetween( 50, 80 );
    $data->building_number_first = $faker->numberBetween( 1, 5 );
    $data->building_number_second = $faker->numberBetween( 50, 80 );
    $data->building_number_third = $faker->numberBetween( 1, 5 );
    $data->building_usetype = 5;
    $data->building_attached = 1;
    $data->building_attached_select = 1;
    $data->building_date_nengou = 1;
    $data->building_date_year = 1985;
    $data->building_date_month = 1;
    $data->building_date_day = 31;
    $data->building_structure = 11;
    $data->building_floor_count = 4;
    $data->building_roof = 63;

    $data->pj_property_id = 1;

    $data->created_at = Carbon::now();
    $data->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
