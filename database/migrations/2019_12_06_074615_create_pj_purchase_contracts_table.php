<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjPurchaseContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_purchase_contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_purchase_target_id')->nullable();
            $table->string('contract_building_number',128)->nullable();
            $table->smallInteger('contract_building_kind')->nullable();
            $table->smallInteger('contract_building_unregistered')->nullable();
            $table->smallInteger('contract_building_unregistered_kind')->nullable();
            $table->decimal('contract_price',12,0)->default(0)->nullable();
            $table->decimal('contract_deposit',12,0)->nullable();
            $table->smallInteger('mediation')->nullable();
            $table->smallInteger('seller')->nullable();
            $table->integer('seller_broker_company_id')->nullable();
            $table->dateTime('contract_date')->nullable();
            $table->dateTime('contract_payment_date')->nullable();
            $table->decimal('contract_price_building',12,4)->nullable();
            $table->smallInteger('contract_price_building_no_tax')->nullable();
            $table->decimal('contract_delivery_money',12,0)->nullable();
            $table->dateTime('contract_delivery_date')->nullable();
            $table->smallInteger('contract_delivery_status')->default(0)->nullable();
            $table->smallInteger('contract_delivery_bank')->default(0)->nullable();
            $table->text('contract_delivery_note')->nullable();
            $table->smallInteger('contract_not_create_documents')->nullable();
            $table->decimal('contract_price_total',12,0)->default(0)->nullable();
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
        Schema::dropIfExists('pj_purchase_contract');
    }
}
