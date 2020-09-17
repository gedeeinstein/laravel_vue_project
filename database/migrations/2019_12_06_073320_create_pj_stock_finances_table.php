<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjStockFinancesTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_stock_finances', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements( 'id' );
            $table->unsignedBigInteger( 'pj_stocking_id' );
            $table->unsignedBigInteger( 'pj_stock_cost_parent_id' )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'total_interest_rate', 12, 0 )->nullable();
            $table->decimal( 'expected_interest_rate', 16, 4 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'banking_fee', 12, 0 )->nullable();
            $table->string( 'banking_fee_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'stamp', 12, 0 )->nullable();
            $table->string( 'stamp_memo', 128 )->nullable();
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
        Schema::dropIfExists( 'pj_stock_finances' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
