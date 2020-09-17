<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjStockTaxesTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_stock_taxes', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements( 'id' );
            $table->unsignedBigInteger( 'pj_stocking_id' );
            $table->unsignedBigInteger( 'pj_stock_cost_parent_id' )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'property_acquisition_tax', 12, 0 )->nullable();
            $table->string( 'property_acquisition_tax_memo', 128 )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'the_following_year_the_city_tax', 12, 0 )->nullable();
            $table->string( 'the_following_year_the_city_tax_memo', 128 )->nullable();
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
        Schema::dropIfExists('pj_stock_taxes');
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
