<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CreateSaleContractsTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'sale_contracts', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mas_section_id');
            // --------------------------------------------------------------
            $table->decimal('contract_price', 12, 0)->nullable();
            $table->decimal('contract_price_building', 12, 0)->nullable();
            $table->unsignedSmallInteger('contract_price_building_no_tax')->nullable();
            // --------------------------------------------------------------
            $table->string('customer_name', 1024)->nullable();
            $table->string('customer_address', 1024)->nullable();
            // --------------------------------------------------------------
            $table->unsignedBigInteger('house_maker')->nullable();
            $table->unsignedBigInteger('register')->nullable();
            $table->unsignedBigInteger('outdoor_facility_manufacturer')->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('person_himself')->nullable();
            $table->unsignedSmallInteger('person_himself_attachment')->nullable();
            // --------------------------------------------------------------
            $table->timestamp('purchase_date')->nullable();
            $table->timestamp('contract_date')->nullable();
            $table->timestamp('payment_date')->nullable();
            // --------------------------------------------------------------
            $table->decimal('delivery_price', 12, 0)->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->unsignedSmallInteger('delivery_status')->nullable();
            $table->unsignedSmallInteger('delivery_account')->nullable();
            $table->string('delivery_note', 1024)->nullable();
            // --------------------------------------------------------------
            $table->decimal('real_estate_tax_income', 12, 0)->nullable();
            $table->timestamp('real_estate_tax_income_date')->nullable();
            $table->unsignedSmallInteger('real_estate_tax_income_status')->nullable();
            $table->unsignedSmallInteger('real_estate_tax_income_account')->nullable();
            // --------------------------------------------------------------
            $table->timestamps();
            // --------------------------------------------------------------
        });
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function down(){
        Schema::dropIfExists( 'sale_contracts' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
