<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjChecklistsTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_checklists', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements( 'id' );
            $table->unsignedBigInteger( 'pj_sheet_id' );
            // --------------------------------------------------------------
            $table->decimal( 'breakthrough_rate', 12, 4 )->nullable();
            $table->decimal( 'effective_area', 12, 4 )->nullable();
            $table->decimal( 'loan_borrowing_amount', 12, 0 )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'procurement_brokerage_fee' )->nullable();
            $table->unsignedSmallInteger( 'resale_brokerage_fee' )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'sales_area' );
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'building_demolition_work' )->nullable();
            $table->unsignedSmallInteger( 'demolition_work_of_retaining_wall' )->nullable();
            $table->unsignedSmallInteger( 'construction_work' )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'driveway' )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'realistic_division' )->nullable();
            $table->unsignedSmallInteger( 'type_of_building' )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'asbestos' )->nullable();
            $table->unsignedSmallInteger( 'many_trees_and_stones' )->nullable();
            $table->unsignedSmallInteger( 'big_storeroom' )->nullable();
            $table->unsignedSmallInteger( 'hard_to_enter' )->nullable();
            $table->unsignedSmallInteger( 'water_draw_count' )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'new_road_type' )->nullable();
            $table->decimal( 'new_road_width', 12, 4 )->nullable();
            $table->decimal( 'new_road_length', 12, 4 )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'side_groove' )->nullable();
            $table->decimal( 'side_groove_length', 12, 4 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'fill', 12, 4 )->nullable();
            $table->unsignedSmallInteger( 'no_fill' )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'retaining_wall' )->nullable();
            $table->unsignedSmallInteger( 'retaining_wall_height' )->nullable();
            $table->decimal( 'retaining_wall_length', 12, 4 )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'development_cost' )->nullable();
            $table->unsignedSmallInteger( 'main_pipe_is_distant' )->nullable();
            $table->unsignedSmallInteger( 'road_sharing' )->nullable();
            $table->unsignedSmallInteger( 'traffic_excavation_consent' )->nullable();
            // --------------------------------------------------------------
            $table->timestamps();
            // --------------------------------------------------------------
        });
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function down(){
        Schema::dropIfExists( 'pj_checklists' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------