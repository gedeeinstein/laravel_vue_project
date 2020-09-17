<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreateMasterRegionsTable extends Migration {
    // ----------------------------------------------------------------------
    /**
    * Run the migrations.
    *
    * @return void
    */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'master_regions', function( Blueprint $table ){
            $table->increments( 'id' );
            $table->string( 'prefecture_code', 4 )->nullable( false );
            $table->string( 'group_code', 10 )->nullable( false );
            $table->string( 'name', 128 )->nullable( false );
            $table->string( 'kana', 128 )->nullable( false );
            $table->smallInteger( 'order' )->unsigned()->nullable( false );
            $table->string( 'type' )->nullable( false )->default('city');
            $table->smallInteger( 'is_enable' )->unsigned()->nullable( false )->default(0);
            $table->smallInteger( 'government_designated_city' )->unsigned()->nullable( false );
            $table->timestamps();
        });
    }
    // ----------------------------------------------------------------------
    /**
    * Reverse the migrations.
    *
    */
    // ----------------------------------------------------------------------
    public function down(){
        Schema::dropIfExists('master_regions');
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
