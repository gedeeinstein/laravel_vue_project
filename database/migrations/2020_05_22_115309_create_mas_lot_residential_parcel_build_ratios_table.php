<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasLotResidentialParcelBuildRatiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_lot_residential_parcel_build_ratios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mas_lot_residential_id')->nullable();
            $table->decimal('value',16,4)->nullable();
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
        Schema::dropIfExists('mas_lot_residential_parcel_build_ratios');
    }
}
