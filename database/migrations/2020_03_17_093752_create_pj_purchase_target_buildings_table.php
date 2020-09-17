<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjPurchaseTargetBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_purchase_target_buildings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_purchase_target_id');
            $table->smallInteger('kind')->nullable();
            $table->smallInteger('exist_unregistered')->nullable();
            $table->smallInteger('purchase_third_person_occupied')->nullable();
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
        Schema::dropIfExists('pj_purchase_target_buildings');
    }
}
