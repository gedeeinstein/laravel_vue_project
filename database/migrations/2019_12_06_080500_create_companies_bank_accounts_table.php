<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesBankAccountsTable extends Migration {
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up(){
        Schema::create('companies_bank_accounts', function( Blueprint $table ){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedSmallInteger('index');
            $table->unsignedSmallInteger('account_kind');
            $table->string('account_number', 64);
            $table->timestamps();
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down(){
        Schema::dropIfExists('companies_bank_accounts');
    }
}
