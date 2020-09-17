<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned();
            $table->string('mediation_note', 256)->nullable();
            $table->string('mediation_sell_note', 256)->nullable();
            $table->string('fee_note', 256)->nullable();
            $table->decimal('total_decided', 16, 4)->nullable();
            $table->string('total_note', 256)->nullable();
            $table->string('total_note_tsubo', 256)->nullable();
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
        Schema::dropIfExists('pj_expanse');
    }
}
