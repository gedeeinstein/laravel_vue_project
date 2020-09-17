<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\PjLotRoadB;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\PjLotRoadA::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create();
    $data = new \stdClass;

    $created = [
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
    $road_b = PjLotRoadB::create($created);

    $data->exists_road_residential = 1;
    $data->parcel_city = '-1';
    $data->parcel_city_extra = 'テスト';
    $data->parcel_town = 'テスト';
    $data->parcel_number_first = $faker->numberBetween( 1, 5 );
    $data->parcel_number_second = $faker->numberBetween( 50, 80 );
    $data->parcel_land_category = '54';
    $data->parcel_size = 300;
    $data->parcel_size_survey = 300;

    $data->pj_property_id = 1;
    $data->pj_lot_road_b_id = $road_b->id;

    $data->created_at = Carbon::now();
    $data->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
