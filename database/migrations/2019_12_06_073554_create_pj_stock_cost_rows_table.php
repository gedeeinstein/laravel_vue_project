<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjStockCostRowsTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_stock_cost_rows', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements( 'id' );
            $table->unsignedBigInteger( 'pj_stock_cost_parent_id' );
            // --------------------------------------------------------------
            $table->string( 'name', 128 )->nullable();
            $table->decimal( 'value', 12, 0 )->nullable();
            $table->string( 'memo', 128 )->nullable();
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
        Schema::dropIfExists( 'pj_stock_cost_rows' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------