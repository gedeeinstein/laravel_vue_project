<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\PjStockConstruction::class, function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $faker = Faker::create('ja_JP');
    // ----------------------------------------------------------------------
    $result->pj_stocking_id = 1;
    $result->pj_stock_cost_parent_id = 1;
    // ----------------------------------------------------------------------
    $result->building_demolition = $faker->numberBetween( 100000, 1000000 );
    $result->building_demolition_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->retaining_wall_demolition = $faker->numberBetween( 100000, 1000000 );
    $result->retaining_wall_demolition_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->transfer_electric_pole = $faker->numberBetween( 100000, 1000000 );
    $result->transfer_electric_pole_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->waterwork_construction = $faker->numberBetween( 100000, 1000000 );
    $result->waterwork_construction_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->fill_work = $faker->numberBetween( 100000, 1000000 );
    $result->fill_work_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->retaining_wall_construction = $faker->numberBetween( 100000, 1000000 );
    $result->retaining_wall_construction_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->road_construction = $faker->numberBetween( 100000, 1000000 );
    $result->road_construction_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->side_groove_construction = $faker->numberBetween( 100000, 1000000 );
    $result->side_groove_construction_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->construction_work_set = $faker->numberBetween( 100000, 1000000 );
    $result->construction_work_set_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->location_designation_application_fee = $faker->numberBetween( 100000, 1000000 );
    $result->location_designation_application_fee_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->development_commissions_fee = $faker->numberBetween( 100000, 1000000 );
    $result->development_commissions_fee_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->cultural_property_research_fee = $faker->numberBetween( 100000, 1000000 );
    $result->cultural_property_research_fee_memo = $faker->text( 128 );
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
$factory->state( App\Models\PjStockConstruction::class, 'init', function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    // ----------------------------------------------------------------------
    $result->pj_stocking_id = null;
    $result->pj_stock_cost_parent_id = null;
    // ----------------------------------------------------------------------
    $result->building_demolition = null;
    $result->building_demolition_memo = null;
    // ----------------------------------------------------------------------
    $result->retaining_wall_demolition = null;
    $result->retaining_wall_demolition_memo = null;
    // ----------------------------------------------------------------------
    $result->transfer_electric_pole = null;
    $result->transfer_electric_pole_memo = null;
    // ----------------------------------------------------------------------
    $result->waterwork_construction = null;
    $result->waterwork_construction_memo = null;
    // ----------------------------------------------------------------------
    $result->fill_work = null;
    $result->fill_work_memo = null;
    // ----------------------------------------------------------------------
    $result->retaining_wall_construction = null;
    $result->retaining_wall_construction_memo = null;
    // ----------------------------------------------------------------------
    $result->road_construction = null;
    $result->road_construction_memo = null;
    // ----------------------------------------------------------------------
    $result->side_groove_construction = null;
    $result->side_groove_construction_memo = null;
    // ----------------------------------------------------------------------
    $result->construction_work_set = null;
    $result->construction_work_set_memo = null;
    // ----------------------------------------------------------------------
    $result->location_designation_application_fee = null;
    $result->location_designation_application_fee_memo = null;
    // ----------------------------------------------------------------------
    $result->development_commissions_fee = null;
    $result->development_commissions_fee_memo = null;
    // ----------------------------------------------------------------------
    $result->cultural_property_research_fee = null;
    $result->cultural_property_research_fee_memo = null;
    // ----------------------------------------------------------------------
    $result->created_at = null;
    $result->updated_at = null;
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
