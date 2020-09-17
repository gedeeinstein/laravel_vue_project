<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasSectionPayoffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_section_payoffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mas_section_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->decimal('profit_rate', 16, 4)->nullable();
            $table->decimal('profit_rate_total', 16, 4)->nullable();
            $table->decimal('profit', 16, 4)->nullable();
            $table->decimal('adjust', 16, 4)->nullable();
            $table->decimal('adjusted', 16, 4)->nullable();
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
        Schema::dropIfExists('mas_section_payoff');
    }
}
