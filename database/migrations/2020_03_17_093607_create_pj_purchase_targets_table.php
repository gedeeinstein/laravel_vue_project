<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjPurchaseTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_purchase_targets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_purchase_id');
            $table->decimal('purchase_price', 12, 0);
            $table->decimal('purchase_deposit', 12, 0)->nullable();
            $table->smallInteger('purchase_not_create_documents')->nullable();
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
        Schema::dropIfExists('pj_purchase_targets');
    }
}
