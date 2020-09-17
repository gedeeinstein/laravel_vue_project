<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreateUsersTable extends Migration {
    // ----------------------------------------------------------------------
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function up(){
        Schema::create( 'users', function( Blueprint $table ){
            // --------------------------------------------------------------
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedInteger( 'user_role_id' )->default(1)->nullable(); // changed from login_authority
            // --------------------------------------------------------------
            // $table->unsignedBigInteger('user_role_id')->nullable();
            // $table->foreign('user_role_id')->references('id')->on('user_roles')->onUpdate('cascade')->onDelete('set null');
            // --------------------------------------------------------------
            $table->string( 'first_name', 128);
            $table->string( 'last_name', 128 );
            $table->string( 'first_name_kana', 128 );
            $table->string( 'last_name_kana', 128 );
            $table->string( 'nickname', 128 )->nullable();
            // --------------------------------------------------------------
            $table->string( 'email', 512 )->unique()->comment('login_account_email')->nullable();
            $table->string( 'password' )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'real_estate_notary_registration' )->nullable();
            $table->unsignedSmallInteger( 'real_estate_notary_office_id' )->nullable();
            $table->unsignedSmallInteger( 'real_estate_notary_prefecture_id' )->nullable();
            $table->string( 'real_estate_notary_number', 14 )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'cooperation_registration' )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'real_estate_information' )->nullable();
            $table->string( 'real_estate_information_text', 1024 )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'registration' )->nullable();
            $table->string( 'registration_text', 1024 )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger('surveying')->nullable();
            $table->string( 'surveying_text', 1024 )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'clothes' )->nullable();
            $table->string( 'clothes_text', 1024 )->nullable();
            // --------------------------------------------------------------
            $table->unsignedSmallInteger( 'other' )->nullable();
            $table->string( 'other_text', 1024 )->nullable();
            // --------------------------------------------------------------
            $table->timestamp( 'email_verified_at' )->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
