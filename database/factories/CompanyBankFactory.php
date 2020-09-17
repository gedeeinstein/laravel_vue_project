<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Generator as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\CompanyBank::class, function( Faker $faker ){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->company_id = 0;
    $result->index = $faker->numberBetween( 1, 99 );
    $result->name_branch = $faker->city;
    $result->name_branch_abbreviation = $faker->stateAbbr;
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
