<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjLotRoadOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_lot_road_owners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('share_denom')->nullable();
            $table->smallInteger('share_number')->nullable();
            $table->string('other')->nullable();
            $table->smallInteger('other_denom')->nullable();
            $table->smallInteger('other_number')->nullable();
            $table->bigInteger('pj_property_owners_id')->unsigned()->nullable();
            $table->bigInteger('pj_lot_road_a_id')->unsigned()->nullable();
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
        Schema::dropIfExists('pj_lot_road_owners');
    }
}
