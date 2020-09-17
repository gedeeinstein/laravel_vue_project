<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\User::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('ja_JP');
    $usFaker = Faker::create('en_US');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->company_id = $faker->numberBetween(1, 31);
    // ----------------------------------------------------------------------
    $result->first_name = $faker->firstName;
    $result->last_name = $faker->lastName;
    $result->first_name_kana = $faker->firstKanaName;
    $result->last_name_kana = $faker->lastKanaName;
    $result->nickname = $faker->name;
    // ----------------------------------------------------------------------
    $result->user_role_id = 1;
    // ----------------------------------------------------------------------
    $result->real_estate_notary_registration = null;
    $result->real_estate_notary_office_id = null;
    $result->real_estate_notary_prefecture_id = $faker->numberBetween( 78, 81 );
    $result->real_estate_notary_number = null;
    // ----------------------------------------------------------------------
    $result->cooperation_registration = 0;
    $result->real_estate_information = 0;
    $result->real_estate_information_text = $faker->text;
    // ----------------------------------------------------------------------
    $result->registration = 0;
    $result->registration_text = $faker->text;
    // ----------------------------------------------------------------------
    $result->surveying = 0;
    $result->surveying_text = $faker->text;
    // ----------------------------------------------------------------------
    $result->clothes = 0;
    $result->clothes_text = $faker->text;
    // ----------------------------------------------------------------------
    $result->other = 0;
    $result->other_text = $faker->text;
    // ----------------------------------------------------------------------
    $result->email = $usFaker->unique()->safeEmail;
    $result->password = bcrypt('12345678');
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});


// --------------------------------------------------------------------------
$factory->state( App\Models\User::class, 'init', function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->company_id = null;
    // ----------------------------------------------------------------------
    $result->first_name = null;
    $result->last_name = null;
    $result->first_name_kana = null;
    $result->last_name_kana = null;
    $result->nickname = null;
    // ----------------------------------------------------------------------
    $result->user_role_id = null;
    // ----------------------------------------------------------------------
    $result->real_estate_notary_registration = null;
    $result->real_estate_notary_office_id = null;
    $result->real_estate_notary_prefecture_id = null;
    $result->real_estate_notary_number = null;
    $result->cooperation_registration = null;
    // ----------------------------------------------------------------------
    $result->real_estate_information = null;
    $result->real_estate_information_text = null;
    // ----------------------------------------------------------------------
    $result->registration = null;
    $result->registration_text = null;
    // ----------------------------------------------------------------------
    $result->surveying = null;
    $result->surveying_text = null;
    // ----------------------------------------------------------------------
    $result->clothes = null;
    $result->clothes_text = null;
    // ----------------------------------------------------------------------
    $result->other = null;
    $result->other_text = null;
    // ----------------------------------------------------------------------
    $result->email = null;
    $result->password = null;
    // ----------------------------------------------------------------------
    $result->created_at = null;
    $result->updated_at = null;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
