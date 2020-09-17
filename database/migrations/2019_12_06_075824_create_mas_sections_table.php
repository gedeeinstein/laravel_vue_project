<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('mas_section_plan_id')->unsigned();
            $table->bigInteger('project_id')->unsigned();
            $table->string('section_number', 64)->nullable();
            $table->string('port_section_number', 64)->nullable();
            $table->decimal('size', 16,4)->nullable();
            $table->decimal('price_budget', 12,0)->nullable();
            $table->smallInteger('condition_build')->nullable();
            $table->string('condition_build_sub', 128)->nullable();
            $table->decimal('profit_budget_rate', 12,0)->nullable();
            $table->decimal('profit_decided', 16,4)->nullable();
            $table->smallInteger('book_price_type')->nullable();
            $table->decimal('book_price', 16,4)->nullable();
            $table->smallInteger('plan_status')->nullable();
            $table->string('name', 128)->nullable();
            $table->decimal('size_total', 16,4)->nullable();
            $table->decimal('price_budget_total', 12,0)->nullable();
            $table->decimal('profit_budget_total', 12,0)->nullable();
            $table->decimal('profit_decided_total', 12,0)->nullable();
            $table->decimal('book_price_total', 12,0)->nullable();
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
        Schema::dropIfExists('mas_sections');
    }
}
