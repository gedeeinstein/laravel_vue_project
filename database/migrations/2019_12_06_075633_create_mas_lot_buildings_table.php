<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasLotBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_lot_buildings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_lot_building_a_id')->nullable();
            $table->unsignedBigInteger('sale_lot_building_id')->nullable();
            $table->smallInteger('exists_land_residential')->nullable();
            $table->smallInteger('parcel_city')->nullable();
            $table->string('parcel_city_extra')->nullable();
            $table->string('parcel_town')->nullable();
            $table->string('parcel_number_first')->nullable();
            $table->string('parcel_number_second')->nullable();
            $table->string('building_number_first')->nullable();
            $table->string('building_number_second')->nullable();
            $table->string('building_number_third')->nullable();
            $table->smallInteger('building_usetype')->nullable();
            $table->smallInteger('building_attached')->nullable();
            $table->smallInteger('building_attached_select')->nullable();
            $table->smallInteger('building_date_nengou')->nullable();
            $table->smallInteger('building_date_year')->nullable();
            $table->smallInteger('building_date_month')->nullable();
            $table->smallInteger('building_date_day')->nullable();
            $table->smallInteger('building_structure')->nullable();
            $table->smallInteger('building_floor_count')->nullable();
            $table->smallInteger('building_roof')->nullable();
            $table->smallInteger('building_type')->nullable();
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
        Schema::dropIfExists('mas_lot_building');
    }
}
