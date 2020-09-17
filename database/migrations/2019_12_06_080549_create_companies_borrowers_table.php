<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesBorrowersTable extends Migration {
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up(){
        Schema::create('companies_borrowers', function( Blueprint $table ){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedSmallInteger('index');
            $table->decimal('loan_limit', 12, 0)->nullable();
            $table->timestamps();
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down(){
        Schema::dropIfExists('companies_borrowers');
    }
}
