<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjSalePlanSectionsTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_sale_plan_sections', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements( 'id' );
            $table->unsignedBigInteger( 'pj_sale_plan_id' )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'divisions_number' )->nullable();
            $table->decimal( 'reference_area', 16, 4 )->nullable();
            $table->decimal( 'planned_area', 16, 4 )->nullable();
            $table->decimal( 'unit_selling_price', 12, 0 )->nullable();
            $table->decimal( 'unit_price', 12, 0 )->nullable();
            $table->decimal( 'brokerage_fee', 12, 0 )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('brokerage_fee_type')->nullable();
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
        Schema::dropIfExists( 'pj_sale_plan_sections' );
    }
    // ----------------------------------------------------------------------
}
