<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasFinanceReturnBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_finance_return_banks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mas_finance_id');
            $table->decimal('amount',12,0)->nullable();
            $table->smallInteger('withdraw_bank')->nullable();
            $table->smallInteger('deposit_bank')->nullable();
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
        Schema::dropIfExists('mas_finance_return_banks');
    }
}
