<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjPurchaseSalePjMemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_purchase_sale_pj_memos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_purchase_sale_id')->nullable();  
            $table->unsignedBigInteger('pj_memo_id')->nullable();  
            $table->string('content',128)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pj_purchasesale_pjmemos');
    }
}
