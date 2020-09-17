<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjPurchaseContractMediationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_purchase_contract_mediations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_purchase_contract_id')->nullable();
            $table->smallInteger('dealtype')->default(0)->nullable();
            $table->smallInteger('balance')->default(0)->nullable();
            // $table->smallInteger('reward');
            $table->decimal('reward',12,4)->default(0)->nullable();
            $table->dateTime('date')->nullable();
            $table->smallInteger('status')->default(0)->nullable();
            $table->smallInteger('bank')->default(0)->nullable();
            $table->integer('trader_company_id')->nullable();
            $table->integer('trader_company_user')->nullable();
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
        Schema::dropIfExists('pj_purchase_contract_mediation');
    }
}
