<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\PjPropertyOwner::class, function(){
    // ----------------------------------------------------------------------
    static $number = 1;

    $faker = Faker::create('en_US');
    $data = new \stdClass;

    $data->name = "所有者{$number}";
    $number +=1;

    $data->pj_property_id = $faker->numberBetween( 1, 2 );

    $data->created_at = Carbon::now();
    $data->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
