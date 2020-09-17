<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\MasterValue;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Default faker generated factory
// --------------------------------------------------------------------------
$factory->define( App\Models\Project::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('ja_JP');
    $districts = MasterValue::where( 'type', 'usedistrict' )->get();
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $created = Carbon::parse( $faker->dateTimeBetween( '-5 years', '-1 week' ));
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $result                              = new \stdClass;
    $result->title                       = $faker->streetName;
    $result->overall_area                = $faker->randomFloat( 2, 200, 5000 );
    $result->fixed_asset_tax_route_value = $faker->numberBetween( 100000, 500000 );
    $result->building_area               = $faker->randomFloat( 2, 100, 400 );
    $result->usage_area                  = (int) $districts->random()->id;
    $result->building_coverage_ratio     = $faker->numberBetween( 0, 80 );
    $result->floor_area_ratio            = $faker->numberBetween( 50, 1300 );
    $result->estimated_closing_date      = Carbon::now()->addDays( $faker->numberBetween( 30, 90 ));
    $result->port_pj_info_number         = null;
    $result->port_contract_number        = null;
    // ----------------------------------------------------------------------
    $result->approval_request            = null;
    // ----------------------------------------------------------------------
    $result->created_at                  = $created;
    $result->updated_at                  = Carbon::now();
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// Empty placeholder factory
// --------------------------------------------------------------------------
$factory->state( App\Models\Project::class, 'init', function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    $result                              = new \stdClass;
    $result->title                       = '';
    $result->overall_area                = 0;
    $result->fixed_asset_tax_route_value = null;
    $result->building_area               = null;
    $result->usage_area                  = null;
    $result->building_coverage_ratio     = null;
    $result->floor_area_ratio            = null;
    $result->estimated_closing_date      = null;
    $result->port_pj_info_number         = null;
    $result->port_contract_number        = null;
    // ----------------------------------------------------------------------
    $result->approval_request            = null;
    // ----------------------------------------------------------------------
    $result->created_at                  = null;
    $result->updated_at                  = null;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Return the result
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
