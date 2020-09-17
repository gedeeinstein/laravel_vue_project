<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasFinancePurchaseAccountMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_finance_purchase_account_mains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mas_finance_id');
            $table->smallInteger('account_main')->nullable();
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
        Schema::dropIfExists('mas_finance_purchase_account_mains');
    }
}
