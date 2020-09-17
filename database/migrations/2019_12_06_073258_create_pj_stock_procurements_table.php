<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjStockProcurementsTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_stock_procurements', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements( 'id' );
            $table->unsignedBigInteger( 'pj_stocking_id' );
            // --------------------------------------------------------------
            $table->decimal( 'price', 12, 0 )->nullable();
            $table->decimal( 'price_tsubo_unit', 12, 0 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'brokerage_fee', 12, 0 )->nullable();
            $table->unsignedSmallInteger( 'brokerage_fee_type' )->nullable();
            $table->string( 'brokerage_fee_memo', 128 )->nullable();
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
        Schema::dropIfExists( 'pj_stock_procurements' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
