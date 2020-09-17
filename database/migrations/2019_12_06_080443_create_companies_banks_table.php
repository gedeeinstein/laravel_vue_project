<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesBanksTable extends Migration {

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up(){
        Schema::create('companies_banks', function( Blueprint $table ){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedSmallInteger('index');
            $table->string('name_branch', 128)->nullable();
            $table->string('name_branch_abbreviation', 128)->nullable();
            $table->timestamps();
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down(){
        Schema::dropIfExists('companies_banks');
    }
}
