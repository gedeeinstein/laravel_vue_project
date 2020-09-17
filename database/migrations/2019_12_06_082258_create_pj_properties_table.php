<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            // ------------------------------------------------------------------
            $table->string('school_primary',128)->nullable();
            $table->smallInteger('school_primary_distance')->nullable();
            $table->string('school_juniorhigh',128)->nullable();
            $table->smallInteger('school_juniorhigh_distance')->nullable();
            $table->decimal('registry_size',16,4)->nullable();
            $table->smallInteger('registry_size_status')->nullable();
            $table->decimal('survey_size',16,4)->nullable();
            $table->smallInteger('survey_size_status')->nullable();
            $table->decimal('fixed_asset_tax_route_value',16,4)->nullable();
            // ------------------------------------------------------------------
            $table->smallInteger('transportation')->nullable();
            $table->string('transportation_station',128)->nullable();
            $table->decimal('transportation_time',16,4)->nullable();
            $table->smallInteger('basic_fire_protection')->nullable();
            $table->smallInteger('height_district')->nullable();
            $table->string('height_district_use',128)->nullable();
            $table->string('restriction_extra',128)->nullable();
            $table->smallInteger('basic_cultural_property_reserves')->default(3)->nullable();
            $table->string('basic_cultural_property_reserves_name',128)->nullable();
            $table->smallInteger('basic_district_planning')->default(3)->nullable();
            $table->string('basic_district_planning_name',128)->nullable();
            $table->smallInteger('basic_scenic_area')->default(3)->nullable();
            $table->smallInteger('basic_landslide')->default(3)->nullable();
            $table->smallInteger('basic_residential_land_development')->default(3)->nullable();
            // ------------------------------------------------------------------
            $table->bigInteger('project_id')->unsigned();
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
        Schema::dropIfExists('pj_properties');
    }
}
