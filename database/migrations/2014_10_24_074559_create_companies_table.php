<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
class CreateCompaniesTable extends Migration {
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    /**
    * Run the migrations.
    *
    * @return void
    */
    // ----------------------------------------------------------------------
    public function up(){
        // ------------------------------------------------------------------
        $types = config('const.COMPANY_TYPES');
        Schema::create('companies', function( Blueprint $table ) use( $types ){
            // --------------------------------------------------------------
            $table->bigIncrements('id');
            // --------------------------------------------------------------
            $table->string('name', 128)->comment('Company Name');
            $table->string('name_kana', 128)->comment('Company Name in Katakana');
            $table->string('name_abbreviation', 128)->nullable()->comment('Company Abbreviation Name');
            // --------------------------------------------------------------
            // Company types
            // --------------------------------------------------------------
            if( !empty( $types )) foreach( $types as $type ){
                $table->smallInteger( $type )->default( false );
            }
            // --------------------------------------------------------------
            $table->string('real_estate_agent_office_main_address', 128)->nullable();
            $table->string('real_estate_agent_office_main_phone_number', 14)->nullable();
            $table->string('real_estate_agent_representative_name', 128)->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('license_authorizer_id')->nullable();
            $table->unsignedSmallInteger('license_update')->nullable();
            $table->unsignedInteger('license_number')->nullable();
            $table->dateTime('license_date')->nullable();
            // --------------------------------------------------------------
            $table->string('real_estate_guarantee_association')->nullable();
            // --------------------------------------------------------------
            $table->string('real_estate_agent_depositary_name', 128)->nullable();
            $table->string('real_estate_agent_depositary_name_address', 128)->nullable();
            // --------------------------------------------------------------
            $table->string('kind_in_house_abbreviation', 128)->nullable();
            // --------------------------------------------------------------
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('companies');
    }
    // ----------------------------------------------------------------------
}
