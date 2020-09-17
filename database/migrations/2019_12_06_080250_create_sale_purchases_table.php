<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CreateSalePurchasesTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'sale_purchases', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_contract_id')->nullable();
            // --------------------------------------------------------------
            $table->decimal('price', 12, 0)->nullable();
            $table->string('price_memo', 1024)->nullable();
            // --------------------------------------------------------------
            $table->decimal('contract_deposit', 12, 0)->nullable();
            $table->string('deposit_memo', 1024)->nullable();
            // --------------------------------------------------------------
            $table->timestamp('contract_date_request')->nullable();
            $table->string('contract_date_request_memo', 1024)->nullable();
            // --------------------------------------------------------------
            $table->timestamp('payment_date_request')->nullable();
            $table->string('payment_date_request_memo', 1024)->nullable();
            // --------------------------------------------------------------
            $table->smallInteger('outdoor_facility')->nullable();
            $table->string('outdoor_facility_memo', 1024)->nullable();
            // --------------------------------------------------------------
            $table->smallInteger('registration')->nullable();
            $table->string('registration_memo', 1024)->nullable();
            // --------------------------------------------------------------
            $table->string('memo', 1024)->nullable();
            // --------------------------------------------------------------
            $table->smallInteger('accept_result')->nullable();
            $table->string('accept_result_memo', 1024)->nullable();
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
        Schema::dropIfExists( 'sale_purchases' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
