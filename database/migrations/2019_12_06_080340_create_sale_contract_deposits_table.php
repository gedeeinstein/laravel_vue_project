<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CreateSaleContractDepositsTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'sale_contract_deposits', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_contract_id');
            // --------------------------------------------------------------
            $table->decimal('price', 12, 0)->nullable();
            $table->timestamp('date')->nullable();
            $table->unsignedSmallInteger('status')->nullable();
            $table->unsignedSmallInteger('account')->nullable();
            $table->string('note', 1024)->nullable();
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
        Schema::dropIfExists( 'sale_contract_deposits' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
