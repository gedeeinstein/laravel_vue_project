<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjMemosTable extends Migration {
    // ----------------------------------------------------------------------
    // Run the migrations
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_memos', function( Blueprint $table ){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id');
            $table->text('content')->nullable();
            $table->smallInteger('disabled')->default( false )->nullable();
            $table->timestamps();
        });
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Reverse the migrations
    // ----------------------------------------------------------------------
    public function down(){
        Schema::dropIfExists( 'pj_memos' );
    }
    // ----------------------------------------------------------------------
}
