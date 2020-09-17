<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\PjLotResidentialB;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\PjLotResidentialA::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('en_US');
    $data = new \stdClass;

    $created = [
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
    $residential_b = PjLotResidentialB::create($created);

    $data->exists_land_residential = 1;
    $data->parcel_city = '-1';
    $data->parcel_city_extra = 'テスト';
    $data->parcel_town = 'テスト';
    $data->parcel_number_first = $faker->numberBetween( 1, 5 );
    $data->parcel_number_second = $faker->numberBetween( 50, 80 );
    $data->parcel_land_category = 51;
    $data->parcel_size = 300;
    $data->parcel_size_survey = 300;

    $data->pj_property_id = 1;
    $data->pj_lot_residential_b_id = $residential_b->id;

    $data->created_at = Carbon::now();
    $data->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
