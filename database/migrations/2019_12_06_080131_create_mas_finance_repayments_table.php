<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasFinanceRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_finance_repayments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mas_finance_unit_id');
            $table->string('section_number', 64)->nullable();
            $table->decimal('money',12,0)->nullable();
            $table->dateTime('date')->nullable();
            $table->smallInteger('account')->nullable();
            $table->text('note')->nullable();
            $table->smallInteger('status')->nullable();
            $table->decimal('book_price_reference',12,0)->nullable();
            $table->decimal('book_price',12,0)->nullable();
            $table->decimal('money_total',12,0)->nullable();
            $table->decimal('book_price_total',12,0)->nullable();
            $table->decimal('book_price_reference_total',12,0)->nullable();
            $table->enum('data_type', ['sales', 'others']);
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
        Schema::dropIfExists('mas_finance_repayments');
    }
}
