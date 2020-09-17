<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------
use App\Helpers\InitialHelper as Initial;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Default faker generated factory
// --------------------------------------------------------------------------
$factory->define( App\Models\Company::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('en_US');
    $company_name = $faker->company;
    $types = config('const.COMPANY_TYPES');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->name              = $company_name;
    $result->name_kana         = $company_name;
    $result->name_abbreviation = Initial::generate( $company_name );
    $result->created_at        = Carbon::now();
    $result->updated_at        = Carbon::now();
    // ----------------------------------------------------------------------
    if( !empty( $types )) foreach( $types as $type ){
        $result->{ $type } = $faker->boolean();
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Real estate agent
    // ----------------------------------------------------------------------
    $agent = 'kind_real_estate_agent';
    if( isset( $result->{ $agent }) && $result->{ $agent }){
        // ------------------------------------------------------------------
        $result->real_estate_agent_representative_name = $faker->name;
        $result->real_estate_agent_office_main_address = $faker->streetAddress;
        $result->real_estate_agent_office_main_phone_number = $faker->tollFreePhoneNumber;
        // ------------------------------------------------------------------
        $result->license_authorizer_id = 0;
        $result->license_update = $faker->numberBetween( 1, 15 );
        $result->license_number = $faker->numberBetween( 1000, 99999 );
        // ------------------------------------------------------------------
        $result->real_estate_guarantee_association = 'other';
        // ------------------------------------------------------------------
        $date = $faker->dateTimeBetween( '-10 years', 'now', config( 'app.timezone' ));
        $result->license_date = $date->format('Y-m-d');
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // In House group
    // ----------------------------------------------------------------------
    $agent = 'kind_in_house';
    if( !empty( $result->{ $agent })){
        $result->kind_in_house_abbreviation = Initial::generate( $company_name );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// Empty placeholder factory
// --------------------------------------------------------------------------
$factory->state( App\Models\Company::class, 'init', function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $types = config('const.COMPANY_TYPES');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Create the base initial properties
    // ----------------------------------------------------------------------
    $result->name              = null;
    $result->name_kana         = null;
    $result->name_abbreviation = null;
    $result->created_at        = null;
    $result->updated_at        = null;
    $result->deleted_at        = null;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Initial types, all set to false
    // ----------------------------------------------------------------------
    if( !empty( $types )) foreach( $types as $type ){
        $result->{ $type } = false;
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Real estate agent
    // ----------------------------------------------------------------------
    $agent = 'real_estate_agent';
    $result->{ "{$agent}_office_main_address" } = null;
    $result->{ "{$agent}_office_main_phone_number" } = null;
    $result->{ "{$agent}_representative_name" } = null;
    // ----------------------------------------------------------------------
    $license = 'license';
    $result->{ "{$license}_date" } = null;
    $result->{ "{$license}_update" } = null;
    $result->{ "{$license}_number" } = null;
    $result->{ "{$license}_authorizer_id" } = null;
    // ----------------------------------------------------------------------
    $result->real_estate_guarantee_association = null;
    $result->{ "{$agent}_depositary_name" } = null;
    $result->{ "{$agent}_depositary_name_address" } = null;
    // ----------------------------------------------------------------------
    $result->kind_in_house_abbreviation = null;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Return the result
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// Empty relations factory
// --------------------------------------------------------------------------
$factory->state( App\Models\Company::class, 'init-relations', function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Assign empty arrays to all relations
    // ----------------------------------------------------------------------
    $result->banks      = array();
    $result->offices    = array();
    $result->accounts   = array();
    $result->borrowers  = array();
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Return the result
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
