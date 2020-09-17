<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasBasicRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_basic_restrictions', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->bigInteger('mas_basic_project_id')->unsigned()->nullable();
            $table->bigInteger('mas_basic_id')->unsigned()->nullable();
            $table->smallInteger('restriction_id')->nullable();
            $table->text('restriction_note')->nullable();
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
        Schema::dropIfExists('mas_basic_restriction');
    }
}
