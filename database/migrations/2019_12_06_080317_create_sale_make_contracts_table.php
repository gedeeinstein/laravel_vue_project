<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CreateSaleMakeContractsTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'sale_make_contracts', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements('id');
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
        Schema::dropIfExists( 'sale_make_contracts' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
