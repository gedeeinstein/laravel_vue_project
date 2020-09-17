<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjSalePlansTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_sale_plans', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_sale_id')->nullable();
            $table->unsignedSmallInteger( 'tab_index' );
            // --------------------------------------------------------------
            $table->string( 'plan_name', 128 );
            $table->string( 'plan_creator', 128 )->nullable();
            $table->string( 'plan_memo', 128 )->nullable();
            $table->unsignedTinyInteger( 'export' )->nullable();
            // --------------------------------------------------------------
            $table->decimal( 'reference_area_total', 16, 4 )->nullable();
            $table->decimal( 'planned_area_total', 16, 4 )->nullable();
            $table->decimal( 'unit_selling_price_total', 12, 0 )->nullable();
            $table->decimal( 'gross_profit_plan', 16, 4 )->nullable();
            $table->decimal( 'gross_profit_total_plan', 16, 4 )->nullable();
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
        Schema::dropIfExists('pj_sale_plans');
    }
    // ----------------------------------------------------------------------
}
