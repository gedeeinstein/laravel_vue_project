<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CreateSaleFeePricesTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'sale_fee_prices', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_fee_id')->nullable();
            $table->unsignedBigInteger('sale_contract_id')->nullable();
            // --------------------------------------------------------------
            $table->decimal('price', 12, 0)->nullable();
            $table->smallInteger('status')->nullable();
            $table->dateTime('date')->nullable();
            $table->smallInteger('account')->nullable();
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
        Schema::dropIfExists( 'sale_fee_prices' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
