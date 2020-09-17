<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasFinanceUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_finance_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mas_finance_id');
            $table->smallInteger('loan_lender')->nullable();
            $table->smallInteger('loan_mortgage')->nullable();
            $table->smallInteger('loan_account')->nullable();
            $table->decimal('loan_balance_money_budget',12,0)->nullable();
            $table->decimal('loan_balance_money',12,0)->nullable();
            $table->decimal('loan_ratio',16,4)->nullable();
            $table->dateTime('loan_period_date')->nullable();
            $table->smallInteger('loan_type')->nullable();
            $table->text('loan_type_date')->nullable();
            $table->decimal('loan_total_budget',12,0)->nullable();
            $table->decimal('loan_total',12,0)->nullable();
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
        Schema::dropIfExists('mas_finance_units');
    }
}
