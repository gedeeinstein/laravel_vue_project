<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjExpenseRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_expense_rows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pj_expense_id')->unsigned();
            $table->string('name', 128)->nullable();
            $table->decimal('decided', 12,0)->nullable();
            $table->date('payperiod')->nullable();
            $table->integer('payee')->nullable();
            $table->enum('payee_type', ['company', 'bank', 'individual'])->nullable();
            $table->string('note', 128)->nullable();
            $table->decimal('paid', 12,0)->nullable();
            $table->dateTime('date')->nullable();
            $table->integer('bank')->nullable();
            $table->boolean('taxfree')->nullable();
            $table->smallInteger('status')->nullable();
            $table->string('screen_name', 45);
            $table->string('screen_index', 45);
            $table->string('data_kind', 45)->nullable();
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
        Schema::dropIfExists('pj_expense_row');
    }
}
