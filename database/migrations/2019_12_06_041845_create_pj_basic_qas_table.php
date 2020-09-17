<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjBasicQasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_basic_qas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->smallInteger('soil_contamination')->nullable();
            $table->smallInteger('cultural_property')->nullable();
            $table->smallInteger('district_planning')->nullable();
            $table->string('building_use_restrictions',128)->nullable();
            $table->decimal('minimum_area',12,4)->nullable();
            $table->smallInteger('difference_in_height')->nullable();
            $table->smallInteger('retaining_wall')->nullable();
            $table->smallInteger('retaining_wall_location')->nullable();
            $table->smallInteger('retaining_wall_breakage')->nullable();
            $table->smallInteger('water')->nullable();
            $table->decimal('water_supply_pipe',12,4)->nullable();
            $table->decimal('water_meter',12,4)->nullable();
            $table->smallInteger('sewage')->nullable();
            $table->smallInteger('private_pipe')->nullable();
            $table->smallInteger('cross_other_land')->nullable();
            $table->smallInteger('insufficient_capacity')->nullable();
            $table->smallInteger('telegraph_pole_hindrance')->nullable();
            $table->smallInteger('telegraph_pole_move');
            $table->smallInteger('telegraph_pole_high_cost');
            $table->smallInteger('width_of_front_road')->nullable();
            $table->smallInteger('plus_popular')->nullable();
            $table->smallInteger('plus_high_price_sale')->nullable();
            $table->string('plus_others',128)->nullable();
            $table->smallInteger('plus_low_price_sale')->nullable();
            $table->smallInteger('minus_landslide_etc')->nullable();
            $table->smallInteger('minus_psychological_defect')->nullable();
            $table->string('minus_others',128)->nullable();
            $table->smallInteger('fixed_survey')->nullable();
            $table->smallInteger('fixed_survey_season')->nullable();
            $table->smallInteger('fixed_survey_reason');
            $table->smallInteger('contact_requirement');
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
        Schema::dropIfExists('pj_basic_qa');
    }
}
