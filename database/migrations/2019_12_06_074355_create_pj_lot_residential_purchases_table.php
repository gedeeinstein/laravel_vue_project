<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjLotResidentialPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_lot_residential_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_lot_residential_a_id')->nullable();
            $table->smallInteger('urbanization_area')->default(0)->nullable();
            $table->smallInteger('urbanization_area_sub')->default(0)->nullable();
            $table->string('urbanization_area_number', 128)->nullable();
            $table->datetime('urbanization_area_date')->nullable();
            $table->smallInteger('urbanization_area_same')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pj_lot_residential_purchase');
    }
}
