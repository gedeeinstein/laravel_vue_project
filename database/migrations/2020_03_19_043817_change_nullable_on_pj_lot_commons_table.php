<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNullableOnPjLotCommonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pj_lot_commons', function (Blueprint $table) {
            $table->unsignedBigInteger('pj_lot_residential_a_id')->nullable()->change();
            $table->unsignedBigInteger('pj_lot_road_a_id')->nullable()->change();
            $table->unsignedBigInteger('pj_lot_building_a_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
