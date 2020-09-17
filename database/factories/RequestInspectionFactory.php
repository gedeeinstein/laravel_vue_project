<?php
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Generator as Faker;
// --------------------------------------------------------------------------
use App\Models\MasterValue;
use App\Models\RequestInspection as Inspection;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( Inspection::class, function( Faker $faker ){
    // ----------------------------------------------------------------------
    $types = MasterValue::where( 'type', 'request_inspection_kind' )->orderBy( 'sort', 'asc' )->get();
    $type = $types->random();
    // ----------------------------------------------------------------------
    $days = collect([ 'yesterday', 'last week', 'last months', 'last sunday', 'first day of last month' ]);
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->user_id = 1;
    $result->project_id = 1;
    $result->context = null;
    $result->kind = (int) $type->key;
    $result->request_datetime = Carbon::parse( $days->random());
    $result->port_number = 1;
    $result->examination = $faker->numberBetween( 1, 3 );
    $result->examination_text = $result->kind === 3 ? $faker->sentence() : null;
    $result->active = true;
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
$factory->state( Inspection::class, 'init', function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->user_id = null;
    $result->project_id = null;
    $result->context = null;
    $result->kind = 1;
    $result->request_datetime = Carbon::now();
    $result->port_number = 1;
    $result->examination = null;
    $result->examination_text = null;
    $result->active = null;
    // ----------------------------------------------------------------------
    $result->created_at = null;
    $result->updated_at = null;
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
