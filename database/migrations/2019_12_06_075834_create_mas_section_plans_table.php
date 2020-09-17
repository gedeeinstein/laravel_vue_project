<?php
// --------------------------------------------------------------------------
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class CreateMasSectionPlansTable extends Migration {
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'mas_section_plans', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements('id');
            $table->bigInteger('mas_setting_id')->unsigned();
            $table->bigInteger('project_id')->unsigned();
            // --------------------------------------------------------------
            $table->smallInteger('number')->unsigned();
            $table->string('plan_number', 32);
            $table->tinyInteger('primary')->unsigned();
            // --------------------------------------------------------------
            $table->timestamps();
            // --------------------------------------------------------------
        });
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function down(){ Schema::dropIfExists('mas_section_plans'); }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
