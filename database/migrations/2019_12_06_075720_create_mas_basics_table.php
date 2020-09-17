<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasBasicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_basics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->smallInteger('transportation')->nullable();
            $table->string('transportation_station',128)->nullable();
            $table->decimal('transportation_distance',12,4)->nullable();
            $table->decimal('transportation_time',12,4)->nullable();
            $table->smallInteger('height_district')->nullable();
            $table->text('height_district_use')->nullable();
            $table->text('school_primary')->nullable();
            $table->decimal('school_primary_distance',12,4)->nullable();
            $table->text('school_juniorhigh',12,4)->nullable();
            $table->decimal('school_juniorhigh_distance',12,4)->nullable();
            $table->decimal('basic_parcel_build_ratio',16,4)->nullable();
            $table->decimal('basic_parcel_floor_ratio',16,4)->nullable();
            $table->smallInteger('basic_fire_protection')->nullable();
            $table->smallInteger('basic_cultural_property_reserves')->nullable();
            $table->text('basic_cultural_property_reserves_name')->nullable();
            $table->smallInteger('basic_district_planning')->nullable();
            $table->text('basic_district_planning_name')->nullable();
            $table->smallInteger('basic_scenic_area')->nullable();
            $table->smallInteger('basic_landslide')->nullable();
            $table->smallInteger('basic_residential_land_development')->nullable();
            $table->smallInteger('project_urbanization_area')->nullable();
            $table->smallInteger('project_urbanization_area_status')->nullable();
            $table->smallInteger('project_urbanization_area_sub')->nullable();
            $table->dateTime('project_urbanization_area_date')->nullable();
            $table->smallInteger('project_current_situation')->nullable();
            $table->text('project_current_situation_other')->nullable();
            $table->smallInteger('project_set_back')->nullable();
            $table->smallInteger('project_private_road')->nullable();
            $table->smallInteger('status')->nullable();
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
        Schema::dropIfExists('mas_basic');
    }
}
