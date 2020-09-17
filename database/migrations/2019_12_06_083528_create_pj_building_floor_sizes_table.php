<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjBuildingFloorSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_building_floor_sizes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('floor_size',16,4)->nullable();
            $table->bigInteger('pj_lot_building_a_id')->unsigned()->nullable();
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
        Schema::dropIfExists('pj_building_floor_sizes');
    }
}
