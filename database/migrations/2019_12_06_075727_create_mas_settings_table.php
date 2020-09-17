<?php
// --------------------------------------------------------------------------
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CreateMasSettingsTable extends Migration {
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'mas_settings', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned();
            // --------------------------------------------------------------
            $table->decimal('decrease_ratio', 16, 4)->nullable();
            // --------------------------------------------------------------
            $table->smallInteger('condition_mediation')->nullable();
            $table->string('condition_mediation_contents', 4096)->nullable();
            // --------------------------------------------------------------
            $table->smallInteger('condition_build')->nullable();
            $table->string('condition_build_contents', 4096)->nullable();
            // --------------------------------------------------------------
            $table->smallInteger('section_count')->nullable();
            // --------------------------------------------------------------
            $table->timestamps();
            // --------------------------------------------------------------
        });
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function down(){ Schema::dropIfExists('mas_settings');}
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
