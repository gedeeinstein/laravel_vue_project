<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CreateSectionSalesTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'section_sales', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mas_section_id');
            // --------------------------------------------------------------
            $table->decimal('area_size', 16, 4)->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('rank')->nullable();
            $table->unsignedSmallInteger('sale_period')->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('unregistered_building')->nullable();
            $table->unsignedSmallInteger('capture_by_third')->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('section_net')->nullable();
            $table->unsignedSmallInteger('section_mediation')->nullable();
            $table->unsignedSmallInteger('section_build_condition')->nullable();
            $table->unsignedSmallInteger('section_signboard')->nullable();
            $table->unsignedBigInteger('section_staff')->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('sell_period_status')->nullable();
            $table->unsignedSmallInteger('sell_period_year')->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('waterworks')->nullable();
            $table->decimal('waterworks_caliber', 16, 4)->nullable();
            $table->unsignedSmallInteger('sewer')->nullable();
            $table->unsignedSmallInteger('gas')->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('road_size_contract_a')->nullable();
            $table->unsignedSmallInteger('road_size_contract_b')->nullable();
            $table->unsignedSmallInteger('road_size_contract_c')->nullable();
            $table->unsignedSmallInteger('road_size_contract_d')->nullable();
            $table->decimal('roadway_distance', 16, 4)->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('road_type_contract_a')->nullable();
            $table->unsignedSmallInteger('road_type_contract_b')->nullable();
            $table->unsignedSmallInteger('road_type_contract_c')->nullable();
            $table->unsignedSmallInteger('road_type_contract_d')->nullable();
            $table->unsignedSmallInteger('road_type_contract_e')->nullable();
            $table->unsignedSmallInteger('road_type_contract_f')->nullable();
            $table->unsignedSmallInteger('road_type_contract_g')->nullable();
            $table->unsignedSmallInteger('road_type_contract_h')->nullable();
            $table->unsignedSmallInteger('road_type_contract_i')->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('road_type_sub2_contract_a')->nullable();
            $table->unsignedSmallInteger('road_type_sub2_contract_b')->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('road_private_burden_contract_a')->nullable();
            $table->unsignedSmallInteger('road_private_burden_contract_b')->nullable();
            $table->decimal('road_private_burden_area_contract', 16, 4)->nullable();
            // --------------------------------------------------------------
            $table->decimal('road_private_burden_share_denom_contract', 16, 4)->nullable();
            $table->decimal('road_private_burden_share_number_contract', 16, 4)->nullable();
            $table->decimal('road_private_burden_amount_contract', 16, 4)->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('road_setback_area_contract')->nullable();
            $table->decimal('road_setback_area_size_contract', 16, 4)->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('road_type_sub1_contract')->nullable();
            $table->unsignedSmallInteger('road_type_sub3_contract')->nullable();
            $table->string('remarks_contract', 4096)->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('road_type')->nullable();
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
        Schema::dropIfExists( 'section_sales' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
