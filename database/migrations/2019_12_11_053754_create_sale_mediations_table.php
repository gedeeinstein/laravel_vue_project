<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleMediationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_mediations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_contract_id')->nullable();
            $table->smallInteger('enable')->nullable();
            $table->smallInteger('dealtype')->nullable();
            $table->smallInteger('balance')->nullable();
            $table->decimal('reward', 16,4)->nullable();
            $table->dateTime('date')->nullable();
            $table->smallInteger('status')->nullable();
            $table->smallInteger('bank')->nullable();
            $table->string('trader', 128)->nullable();
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
        Schema::dropIfExists('sale_mediations');
    }
}
