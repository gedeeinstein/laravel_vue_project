<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->define( App\Models\PjChecklist::class, function(){
    // ----------------------------------------------------------------------
    $faker = Faker::create('ja_JP');
    $money = collect([ 1000000, 2000000, 3500000, 6500000, 9800000 ]);

    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->pj_sheet_id = 1;
    $result->breakthrough_rate = $faker->randomFloat( 2, 10, 90 );
    $result->effective_area = $faker->numberBetween( 100, 300 );
    $result->loan_borrowing_amount = $money->random();
    $result->procurement_brokerage_fee = $faker->numberBetween( 1, 3 );
    $result->resale_brokerage_fee = $faker->numberBetween( 1, 3 );
    $result->sales_area = $faker->numberBetween( 1, 2 );
    // ----------------------------------------------------------------------
    $result->building_demolition_work = $faker->boolean();
    $result->demolition_work_of_retaining_wall = $faker->boolean();
    $result->construction_work = $faker->numberBetween( 1, 3 );
    $result->driveway = $faker->boolean();
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // S3
    // ----------------------------------------------------------------------
    $result->realistic_division = $faker->numberBetween( 1, 2 );
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // S4
    // ----------------------------------------------------------------------
    if( $result->building_demolition_work ){
        $result->type_of_building = $faker->numberBetween( 1, 3 );
        $result->asbestos = $faker->numberBetween( 1, 2 );
        $result->many_trees_and_stones = $faker->boolean();
        $result->big_storeroom = $faker->boolean();
        $result->hard_to_enter = $faker->boolean();
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // S5 - Empty
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // S6
    // ----------------------------------------------------------------------
    if( 2 === $result->construction_work ){
        // ------------------------------------------------------------------
        $result->water_draw_count = $faker->numberBetween( 100, 9000 );
        $result->new_road_type = $faker->numberBetween( 1, 3 );
        // ------------------------------------------------------------------
        if( 1 === $result->new_road_type || 2 === $result->new_road_type ){
            $result->new_road_width = $faker->numberBetween( 6, 12 );
            $result->new_road_length = $faker->numberBetween( 100, 200 );
        }
        // ------------------------------------------------------------------
        $result->side_groove = $faker->numberBetween( 1, 3 );
        // ------------------------------------------------------------------
        if( 1 === $result->side_groove || 2 === $result->side_groove ){
            $result->side_groove_length = $faker->numberBetween( 5, 20 );
            $result->fill = $faker->numberBetween( 5, 20 );
            $result->no_fill = $faker->boolean();
        }
        // ------------------------------------------------------------------
        $result->retaining_wall = $faker->numberBetween( 1, 2 );
        // ------------------------------------------------------------------
        if( 1 === $result->retaining_wall ){
            $result->retaining_wall_height = $faker->numberBetween( 1, 7 );
            $result->retaining_wall_length = $faker->numberBetween( 4, 8 );
        }
        // ------------------------------------------------------------------
        $result->development_cost = $faker->numberBetween( 1, 4 );
        $result->main_pipe_is_distant = $faker->boolean();
        // ------------------------------------------------------------------
        if( $result->driveway ){
            $result->road_sharing = $faker->numberBetween( 1, 2 );
            $result->traffic_excavation_consent = $faker->numberBetween( 1, 2 );
        }
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
    $result->created_at = Carbon::now();
    $result->updated_at = Carbon::now();
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
$factory->state( App\Models\PjChecklist::class, 'init', function(){
    // ----------------------------------------------------------------------
    $result = new \stdClass;
    $result->pj_sheet_id = null;
    $result->breakthrough_rate = null;
    $result->effective_area = null;
    $result->loan_borrowing_amount = null;
    $result->procurement_brokerage_fee = null;
    $result->resale_brokerage_fee = null;
    $result->sales_area = 0;
    // ----------------------------------------------------------------------
    $result->building_demolition_work = null;
    $result->demolition_work_of_retaining_wall = null;
    $result->construction_work = null;
    $result->driveway = null;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // S3
    // ----------------------------------------------------------------------
    $result->realistic_division = null;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // S4
    // ----------------------------------------------------------------------
    $result->type_of_building = null;
    $result->asbestos = null;
    $result->many_trees_and_stones = null;
    $result->big_storeroom = null;
    $result->hard_to_enter = null;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // S5 - Empty
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // S6
    // ----------------------------------------------------------------------
    $result->water_draw_count = null;
    $result->new_road_type = null;
    $result->new_road_width = null;
    $result->new_road_length = null;
    // ----------------------------------------------------------------------
    $result->side_groove = null;
    // ----------------------------------------------------------------------
    $result->side_groove_length = null;
    $result->fill = null;
    $result->no_fill = null;
    // ----------------------------------------------------------------------
    $result->retaining_wall = null;
    $result->retaining_wall_height = null;
    $result->retaining_wall_length = null;
    // ----------------------------------------------------------------------
    $result->development_cost = null;
    $result->main_pipe_is_distant = null;
    // ----------------------------------------------------------------------
    $result->road_sharing = null;
    $result->traffic_excavation_consent = null;
    // ----------------------------------------------------------------------
    $result->created_at = null;
    $result->updated_at = null;
    // ----------------------------------------------------------------------
    return (array) $result;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
