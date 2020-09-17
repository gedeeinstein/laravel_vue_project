<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasFinanceExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_finance_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mas_finance_id');
            $table->decimal('decided', 12,0)->nullable();
            $table->date('payperiod')->nullable();
            $table->integer('payee')->nullable();
            $table->enum('payee_type', ['company', 'bank', 'individual'])->nullable();
            $table->text('note')->nullable();
            $table->decimal('paid', 12,0)->nullable();
            $table->dateTime('date')->nullable();
            $table->integer('bank')->nullable();
            $table->boolean('taxfree')->nullable();
            $table->smallInteger('status')->nullable();
            $table->string('category_index', 45);
            $table->string('display_name', 45);
            $table->bigInteger('additional_id')->unsigned()->nullable();
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
        Schema::dropIfExists('mas_finance_expenses');
    }
}
