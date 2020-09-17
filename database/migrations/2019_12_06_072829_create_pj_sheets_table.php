<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreatePjSheetsTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'pj_sheets', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements( 'id' );
            $table->unsignedBigInteger( 'project_id' );
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'tab_index' );
            $table->unsignedSmallInteger( 'is_reflecting_in_budget' )->nullable();
            // --------------------------------------------------------------
            $table->string( 'name', 128 )->nullable();
            $table->string( 'creator_name', 128 )->nullable();
            $table->string( 'memo', 128 )->nullable();
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
        Schema::dropIfExists( 'pj_sheets' );
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------