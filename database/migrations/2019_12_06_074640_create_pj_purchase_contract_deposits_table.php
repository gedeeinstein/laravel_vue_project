<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjPurchaseContractDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_purchase_contract_deposits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_purchase_contract_id')->nullable();
            $table->decimal('price',12,4)->nullable();
            $table->dateTime('date')->nullable();
            $table->smallInteger('status')->default(0)->nullable();
            $table->smallInteger('account')->default(0)->nullable();
            $table->string('note',128)->nullable();
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
        Schema::dropIfExists('pj_purchase_contract_deposit');
    }
}
