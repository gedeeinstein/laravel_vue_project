<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjStockConstructionsTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_stock_constructions', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements( 'id' );
            $table->unsignedBigInteger( 'pj_stocking_id' );
            $table->unsignedBigInteger( 'pj_stock_cost_parent_id' )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'building_demolition', 12, 0 )->nullable();
            $table->string( 'building_demolition_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'retaining_wall_demolition', 12, 0 )->nullable();
            $table->string( 'retaining_wall_demolition_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'transfer_electric_pole', 12, 0 )->nullable();
            $table->string( 'transfer_electric_pole_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'waterwork_construction', 12, 0 )->nullable();
            $table->string( 'waterwork_construction_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'fill_work', 12, 0 )->nullable();
            $table->string( 'fill_work_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'retaining_wall_construction', 12, 0 )->nullable();
            $table->string( 'retaining_wall_construction_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'road_construction', 12, 0 )->nullable();
            $table->string( 'road_construction_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'side_groove_construction', 12, 0 )->nullable();
            $table->string( 'side_groove_construction_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'construction_work_set', 12, 0 )->nullable();
            $table->string( 'construction_work_set_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'location_designation_application_fee', 12, 0 )->nullable();
            $table->string( 'location_designation_application_fee_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'development_commissions_fee', 12, 0 )->nullable();
            $table->string( 'development_commissions_fee_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'cultural_property_research_fee', 12, 0 )->nullable();
            $table->string( 'cultural_property_research_fee_memo', 128 )->nullable();
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
        Schema::dropIfExists( 'pj_stock_constructions' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
