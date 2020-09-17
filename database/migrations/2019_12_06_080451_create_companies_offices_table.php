<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesOfficesTable extends Migration {
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up(){
        Schema::create('companies_offices', function( Blueprint $table ){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedSmallInteger('index');
            $table->string('name', 128);
            $table->string('address', 128)->nullable();
            $table->string('number', 14)->nullable();
            $table->timestamps();
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down(){
        Schema::dropIfExists('companies_offices');
    }
}
