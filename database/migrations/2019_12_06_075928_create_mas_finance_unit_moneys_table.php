<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasFinanceUnitMoneysTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'mas_finance_unit_moneys', function( Blueprint $table ){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mas_finance_unit_id');
            $table->decimal('loan_money',12,0)->nullable();
            $table->dateTime('loan_date')->nullable();
            $table->text('loan_note')->nullable();
            $table->smallInteger('loan_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists( 'mas_finance_unit_moneys' );
    }
}
