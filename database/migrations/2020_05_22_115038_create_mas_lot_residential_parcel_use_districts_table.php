<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasLotResidentialParcelUseDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_lot_residential_parcel_use_districts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mas_lot_residential_id')->nullable();
            $table->string('value', 64)->nullable();
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
        Schema::dropIfExists('mas_lot_residential_parcel_use_districts');
    }
}
