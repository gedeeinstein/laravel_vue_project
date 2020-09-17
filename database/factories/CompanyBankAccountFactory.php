<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Generator as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\CompanyBankAccount::class, function( Faker $faker ){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->bank_id = 0;
    $result->company_id = 0;
    $result->index = $faker->numberBetween( 1, 99 );
    $result->account_kind = $faker->numberBetween( 1, 2 );
    $result->account_number = $faker->bankAccountNumber;
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
