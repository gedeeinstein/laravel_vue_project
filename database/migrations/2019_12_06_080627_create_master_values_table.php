<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreateMasterValuesTable extends Migration {
    // ----------------------------------------------------------------------
    /**
    * Run the migrations.
    *
    * @return void
    */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create('master_values', function( Blueprint $table ){
            $table->smallIncrements( 'id' )->nullable( false );
            $table->string( 'type', 64 )->nullable( false );
            $table->string( 'key', 64 )->nullable( false );
            $table->string( 'value', 256 )->nullable( false );
            $table->smallInteger( 'sort' )->nullable( false )->default(0);
            $table->smallInteger( 'masterdeleted' )->nullable( false )->default(0);
            $table->timestamps();
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
        Schema::dropIfExists('master_values');
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
