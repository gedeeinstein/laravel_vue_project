<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CreateRequestInspectionsTable extends Migration {
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'request_inspections', function( Blueprint $table ){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('sheet_id')->nullable();
            $table->string('context', 255 )->nullable();
            $table->unsignedSmallInteger('kind');
            $table->timestamp('request_datetime');
            $table->unsignedSmallInteger('port_number');
            $table->unsignedSmallInteger('examination')->nullable();
            $table->string('examination_text', 256)->nullable();
            $table->unsignedTinyInteger('active')->nullable();
            $table->timestamps();
        });
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function down(){
        Schema::dropIfExists( 'request_inspections' );
    }
    // ----------------------------------------------------------------------
}
