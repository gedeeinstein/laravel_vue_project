<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjPurchaseResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_purchase_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_purchase_target_id')->nullable();
            $table->smallInteger('notices_a')->nullable();
            $table->string('notices_a_text', 128)->nullable();
            $table->smallInteger('notices_b')->nullable();
            $table->string('notices_b_text', 128)->nullable();
            $table->smallInteger('notices_c')->nullable();
            $table->string('notices_c_text', 128)->nullable();
            $table->smallInteger('notices_d')->nullable();
            $table->string('notices_d_text', 128)->nullable();
            $table->smallInteger('notices_e')->nullable();
            $table->string('notices_e_text', 128)->nullable();
            $table->smallInteger('request_permission_a')->nullable();
            $table->smallInteger('request_permission_b')->nullable();
            $table->smallInteger('request_permission_c')->nullable();
            $table->smallInteger('request_permission_d')->nullable();
            $table->smallInteger('request_permission_e')->nullable();
            $table->smallInteger('desired_contract_terms_a')->nullable();
            $table->smallInteger('desired_contract_terms_b')->nullable();
            $table->smallInteger('desired_contract_terms_c')->nullable();
            $table->smallInteger('desired_contract_terms_d')->nullable();
            $table->smallInteger('desired_contract_terms_e')->nullable();
            $table->smallInteger('desired_contract_terms_f')->nullable();
            $table->smallInteger('desired_contract_terms_g')->nullable();
            $table->smallInteger('desired_contract_terms_h')->nullable();
            $table->smallInteger('desired_contract_terms_i')->nullable();
            $table->smallInteger('desired_contract_terms_j')->nullable();
            $table->smallInteger('desired_contract_terms_k')->nullable();
            $table->smallInteger('desired_contract_terms_l')->nullable();
            $table->smallInteger('desired_contract_terms_m')->nullable();
            $table->string('desired_contract_terms_m_text', 128)->nullable();
            $table->smallInteger('desired_contract_terms_n')->nullable();
            $table->smallInteger('desired_contract_terms_o')->nullable();
            $table->smallInteger('desired_contract_terms_p')->nullable();
            $table->smallInteger('desired_contract_terms_q')->nullable();
            $table->smallInteger('desired_contract_terms_r_1')->nullable();
            $table->string('desired_contract_terms_r_1_text', 128)->nullable();
            $table->smallInteger('desired_contract_terms_r_2')->nullable();
            $table->string('desired_contract_terms_r_2_text', 128)->nullable();
            $table->smallInteger('desired_contract_terms_s')->nullable();
            $table->smallInteger('desired_contract_terms_t')->nullable();
            $table->smallInteger('desired_contract_terms_u')->nullable();
            $table->smallInteger('desired_contract_terms_v')->nullable();
            $table->smallInteger('desired_contract_terms_w')->nullable();
            $table->smallInteger('desired_contract_terms_x')->nullable();
            $table->smallInteger('desired_contract_terms_y')->nullable();
            $table->smallInteger('desired_contract_terms_z')->nullable();
            $table->smallInteger('desired_contract_terms_aa')->nullable();
            $table->smallInteger('desired_contract_terms_ab')->nullable();
            $table->smallInteger('desired_contract_terms_ac')->nullable();
            $table->smallInteger('desired_contract_terms_ad')->nullable();
            $table->smallInteger('desired_contract_terms_ae')->nullable();
            $table->smallInteger('desired_contract_terms_af')->nullable();
            $table->smallInteger('desired_contract_terms_ag')->nullable();
            $table->smallInteger('desired_contract_terms_ah')->nullable();
            $table->smallInteger('desired_contract_terms_ai')->nullable();
            $table->smallInteger('desired_contract_terms_aj')->nullable();
            $table->smallInteger('desired_contract_terms_ak')->nullable();
            $table->smallInteger('desired_contract_terms_al')->nullable();
            $table->smallInteger('desired_contract_terms_am')->nullable();
            $table->smallInteger('desired_contract_terms_an')->nullable();
            $table->smallInteger('contract_update')->default(1);
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
        Schema::dropIfExists('pj_purchase_responses');
    }
}
