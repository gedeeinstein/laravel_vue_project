<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CreatePjPurchaseContractCreateBuildingsTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_purchase_contract_create_buildings', function( Blueprint $table ){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_purchase_contract_create_id')->nullable();
            $table->unsignedBigInteger('pj_lot_building_a_id')->nullable();
            $table->string('house_number', 128 )->nullable();
            $table->string('building_number', 128 )->nullable();
            $table->string('building_parcel', 128 )->nullable();
            $table->string('building_address', 128 )->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('pj_purchase_contract_create_buildings');
    }
    // ----------------------------------------------------------------------
}
