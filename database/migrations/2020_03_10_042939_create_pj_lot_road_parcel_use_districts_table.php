<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjLotRoadParcelUseDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_lot_road_parcel_use_districts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value', 64)->nullable();
            $table->unsignedBigInteger('pj_lot_road_a_id')->nullable();
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
        Schema::dropIfExists('pj_lot_road_parcel_use_districts');
    }
}
