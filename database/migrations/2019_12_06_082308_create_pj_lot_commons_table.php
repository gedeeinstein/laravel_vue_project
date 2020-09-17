<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjLotCommonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_lot_commons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pj_property_id')->unsigned(); //edited at 13-02-2020
            $table->bigInteger('pj_lot_residential_a_id')->unsigned();
            $table->bigInteger('pj_lot_road_a_id')->unsigned();
            $table->bigInteger('pj_lot_building_a_id')->unsigned();
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
        Schema::dropIfExists('pj_lot_common');
    }
}
