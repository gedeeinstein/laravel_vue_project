<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjLotResidentialBTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_lot_residential_b', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('fire_protection')->nullable()->default(0);
            $table->smallInteger('fire_protection_same')->nullable()->default(1);
            $table->smallInteger('cultural_property_reserves')->nullable()->default(3);
            $table->smallInteger('cultural_property_reserves_same')->nullable()->default(1);
            $table->string('cultural_property_reserves_name',128)->nullable();
            $table->smallInteger('district_planning')->nullable()->default(3);
            $table->smallInteger('district_planning_same')->nullable()->default(1);
            $table->string('district_planning_name',128)->nullable();
            $table->smallInteger('scenic_area')->nullable()->default(3);
            $table->smallInteger('scenic_area_same')->nullable()->default(1);
            $table->smallInteger('landslide')->nullable()->default(3);
            $table->smallInteger('landslide_same')->nullable()->default(1);
            $table->smallInteger('residential_land_development')->nullable()->default(3);
            $table->smallInteger('residential_land_development_same')->nullable()->default(1);
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
        Schema::dropIfExists('pj_lot_residential_b');
    }
}
