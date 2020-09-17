<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\CompanyOffice::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('en_US');
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->company_id = 0;
    $result->index = $faker->numberBetween( 1, 99 );
    $result->name = $faker->city;
    $result->address = $faker->streetAddress;
    $result->number = $faker->tollFreePhoneNumber;
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
