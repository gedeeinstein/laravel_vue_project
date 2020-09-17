<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjLotRoadATable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_lot_road_a', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('exists_road_residential');
            $table->smallInteger('parcel_city')->nullable();
            $table->string('parcel_city_extra',128)->nullable();
            $table->string('parcel_town',128)->nullable();
            $table->string('parcel_number_first',128)->nullable();
            $table->string('parcel_number_second',128)->nullable();
            $table->smallInteger('parcel_land_category')->nullable();
            $table->decimal('parcel_size',16,4)->nullable();
            $table->decimal('parcel_size_survey',16,4)->nullable();
            $table->bigInteger('pj_property_id')->unsigned()->nullable();
            $table->bigInteger('pj_lot_road_b_id')->unsigned()->nullable();
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
        Schema::dropIfExists('pj_lot_road_a');
    }
}
