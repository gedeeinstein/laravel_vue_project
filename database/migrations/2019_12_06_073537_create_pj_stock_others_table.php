<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjStockOthersTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_stock_others', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements( 'id' );
            $table->unsignedBigInteger( 'pj_stocking_id' );
            $table->unsignedBigInteger( 'pj_stock_cost_parent_id' )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'referral_fee', 12, 0 )->nullable();
            $table->string( 'referral_fee_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'eviction_fee', 12, 0 )->nullable();
            $table->string( 'eviction_fee_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'water_supply_subscription', 12, 0 )->nullable();
            $table->string( 'water_supply_subscription_memo', 128 )->nullable();
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
        Schema::dropIfExists( 'pj_stock_others' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
