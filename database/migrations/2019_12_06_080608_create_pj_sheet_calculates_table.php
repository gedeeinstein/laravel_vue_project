<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjSheetCalculatesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('pj_sheet_calculates', function( Blueprint $table ){
            $table->increments('id');
            $table->unsignedInteger('field_id')->nullable();
            $table->decimal('value', 12, 0)->nullable();
            $table->decimal('default_value', 12, 0)->nullable();
            $table->string('serial', 12)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('pj_sheet_calculates');
    }
}
