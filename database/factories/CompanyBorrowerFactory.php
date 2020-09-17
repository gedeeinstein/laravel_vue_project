<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Generator as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\CompanyBorrower::class, function( Faker $faker ){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->bank_id = 0;
    $result->company_id = 0;
    $result->index = $faker->numberBetween( 1, 99 );
    $result->loan_limit = $faker->numberBetween( 100000, 9000000 );
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
