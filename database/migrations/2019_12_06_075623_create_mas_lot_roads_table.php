<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasLotRoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_lot_roads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_lot_road_a_id')->nullable();
            $table->unsignedBigInteger('sale_lot_road_id')->nullable();
            $table->smallInteger('exists_road_residential')->nullable();
            $table->smallInteger('parcel_city')->nullable();
            $table->string('parcel_city_extra')->nullable();
            $table->string('parcel_town')->nullable();
            $table->string('parcel_number_first')->nullable();
            $table->string('parcel_number_second')->nullable();
            $table->smallInteger('parcel_land_category')->nullable();
            $table->smallInteger('fire_protection')->nullable();
            $table->smallInteger('fire_protection_same')->nullable();
            $table->smallInteger('cultural_property_reserves')->nullable();
            $table->smallInteger('cultural_property_reserves_same')->nullable();
            $table->string('cultural_property_reserves_name')->nullable();
            $table->smallInteger('district_planning')->nullable();
            $table->smallInteger('district_planning_same')->nullable();
            $table->string('district_planning_name')->nullable();
            $table->smallInteger('scenic_area_same')->nullable();
            $table->smallInteger('landslide')->nullable();
            $table->smallInteger('landslide_same')->nullable();
            $table->smallInteger('residential_land_development')->nullable();
            $table->smallInteger('residential_land_development_same')->nullable();
            $table->smallInteger('urbanization_area')->nullable();
            $table->smallInteger('urbanization_area_sub')->nullable();
            $table->string('urbanization_area_number')->nullable();
            $table->dateTime('urbanization_area_date')->nullable();
            $table->smallInteger('urbanization_area_same')->nullable();
            $table->smallInteger('project_current_situation')->nullable();
            $table->string('project_current_situation_other')->nullable();
            $table->smallInteger('project_current_situation_same_to_basic')->nullable();
            $table->smallInteger('project_set_back')->nullable();
            $table->smallInteger('project_set_back_same_to_basic')->nullable();
            $table->smallInteger('project_private_road')->nullable();
            $table->smallInteger('project_private_road_same_to_basic')->nullable();
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
        Schema::dropIfExists('mas_lot_road');
    }
}
