<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CreateSaleFeesTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'sale_fees', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_contract_id')->nullable();
            // --------------------------------------------------------------
            $table->smallInteger('enable')->nullable();
            $table->text('customer')->nullable();
            $table->text('note')->nullable();
            $table->smallInteger('balance')->nullable();
            $table->smallInteger('receipt_company')->nullable();
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
        Schema::dropIfExists( 'sale_fees' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
