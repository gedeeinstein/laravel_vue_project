<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjStockSurveysTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_stock_surveys', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements( 'id' );
            $table->unsignedBigInteger( 'pj_stocking_id' );
            $table->unsignedBigInteger( 'pj_stock_cost_parent_id' )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'fixed_survey', 12, 0 )->nullable();
            $table->string( 'fixed_survey_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'divisional_registration', 12, 0 )->nullable();
            $table->string( 'divisional_registration_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'boundary_pile_restoration', 12, 0 )->nullable();
            $table->string( 'boundary_pile_restoration_memo', 128 )->nullable();
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
        Schema::dropIfExists( 'pj_stock_surveys' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
