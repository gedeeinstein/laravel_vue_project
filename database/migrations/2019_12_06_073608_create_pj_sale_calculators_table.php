<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjSaleCalculatorsTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_sale_calculators', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements( 'id' );
            $table->unsignedBigInteger( 'pj_sale_id' )->nullable();
            // --------------------------------------------------------------
            $table->unsignedInteger( 'sales_divisions' )->nullable();
            $table->decimal( 'unit_average_area', 16, 4 )->nullable();
            $table->decimal( 'rate_of_return', 16, 4 )->nullable();
            $table->decimal( 'sales_unit_price', 12, 0 )->nullable();
            $table->decimal( 'unit_average_price', 12, 0 )->nullable();
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
        Schema::dropIfExists( 'pj_sale_calculators' );
    }
    // ----------------------------------------------------------------------
}
