<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\PjSheet::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('ja_JP');
    $names = collect( $faker->words( 6 ));
    // $names = $names->merge([ '仲介あり', '工事あり' ]);
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->tab_index = 1;
    $result->project_id = 1;
    $result->is_reflecting_in_budget = false;
    $result->name = ucfirst( $names->random());
    $result->creator_name = $faker->lastKanaName;
    $result->memo = $faker->sentence();
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
$factory->state( App\Models\PjSheet::class, 'init', function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->tab_index = null;
    $result->project_id = null;
    $result->is_reflecting_in_budget = false;
    $result->name = null;
    $result->creator_name = null;
    $result->memo = null;
    // ----------------------------------------------------------------------
    $result->created_at = null;
    $result->updated_at = null;
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
