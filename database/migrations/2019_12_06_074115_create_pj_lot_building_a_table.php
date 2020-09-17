<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjLotBuildingATable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_lot_building_a', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('exists_building_residential');
            $table->smallInteger('parcel_city')->nullable();
            $table->string('parcel_city_extra',128)->nullable();
            $table->string('parcel_town',128)->nullable();
            $table->smallInteger('parcel_number_first')->nullable();
            $table->smallInteger('parcel_number_second')->nullable();
            $table->smallInteger('building_number_first')->nullable();
            $table->smallInteger('building_number_second')->nullable();
            $table->smallInteger('building_number_third')->nullable();
            $table->smallInteger('building_usetype')->nullable();
            $table->boolean('building_attached')->nullable();
            $table->smallInteger('building_attached_select')->nullable();
            $table->smallInteger('building_date_nengou')->nullable();
            $table->smallInteger('building_date_year')->nullable();
            $table->smallInteger('building_date_month')->nullable();
            $table->smallInteger('building_date_day')->nullable();
            $table->smallInteger('building_structure')->nullable();
            $table->smallInteger('building_floor_count')->nullable();
            $table->smallInteger('building_roof')->nullable();
            $table->bigInteger('pj_property_id')->unsigned()->nullable();
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
        Schema::dropIfExists('pj_lot_building_a');
    }
}
