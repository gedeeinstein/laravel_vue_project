<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjLotRoadPurchaseCreatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_lot_road_purchase_creates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_lot_contractor_id')->nullable();
            $table->smallInteger('pj_lot_road_a_id')->nullable();
            $table->smallInteger('purchase_equity')->default('1');
            $table->string('purchase_equity_text')->nullable();
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
        Schema::dropIfExists('pj_lot_road_purchase_creates');
    }
}
