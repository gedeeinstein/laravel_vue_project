<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjLotResidentialParcelBuildRatiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_lot_residential_parcel_build_ratios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('value',16,4)->nullable();
            $table->unsignedBigInteger('pj_lot_residential_a_id')->nullable();
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
        Schema::dropIfExists('pj_lot_residential_parcel_build_ratios');
    }
}
