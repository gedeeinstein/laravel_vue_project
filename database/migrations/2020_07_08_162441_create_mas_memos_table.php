<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasMemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_memos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mas_section_id');
            $table->string('content', 700)->nullable();
            $table->unsignedBigInteger('author');
            $table->boolean('is_deleted');
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
        Schema::dropIfExists('mas_memos');
    }
}
