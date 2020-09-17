<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjStockRegistersTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_stock_registers', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements( 'id' );
            $table->unsignedBigInteger( 'pj_stocking_id' );
            $table->unsignedBigInteger( 'pj_stock_cost_parent_id' )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'transfer_of_ownership', 12, 0 )->nullable();
            $table->string( 'transfer_of_ownership_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'mortgage_setting', 12, 0 )->nullable();
            $table->decimal( 'mortgage_setting_plan', 12, 0 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'fixed_assets_tax', 12, 0 )->nullable();
            $table->dateTime( 'fixed_assets_tax_date' )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'loss', 12, 0 )->nullable();
            $table->string( 'loss_memo', 128 )->nullable();
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
        Schema::dropIfExists('pj_stock_registers');
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
