<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjPurchaseDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_purchase_docs', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('pj_purchase_target_id')->nullable();
          $table->smallInteger('heads_up_a')->nullable();
          $table->smallInteger('heads_up_b')->nullable();
          $table->smallInteger('heads_up_c')->default(3);
          $table->smallInteger('heads_up_d')->default(3);
          $table->smallInteger('heads_up_e')->nullable();
          $table->smallInteger('heads_up_f')->nullable();
          $table->smallInteger('heads_up_g')->nullable();
          $table->smallInteger('heads_up_status')->nullable();
          $table->string('heads_up_memo',128)->nullable();
          $table->smallInteger('properties_description_a')->nullable();
          $table->smallInteger('properties_description_b')->nullable();
          $table->smallInteger('properties_description_c')->default(2);
          $table->smallInteger('properties_description_d')->nullable();
          $table->smallInteger('properties_description_e')->nullable();
          $table->smallInteger('properties_description_f')->nullable();
          $table->smallInteger('properties_description_status')->nullable();
          $table->string('properties_description_memo',128)->nullable();
          // $table->smallInteger('front_road_a')->nullable();
          // $table->smallInteger('front_road_b')->nullable();
          // $table->smallInteger('front_road_c')->nullable();
          // $table->smallInteger('front_road_d')->nullable();
          // $table->smallInteger('front_road_e')->nullable();
          $table->smallInteger('road_size_contract_a')->nullable();
          $table->smallInteger('road_size_contract_b')->nullable();
          $table->smallInteger('road_size_contract_c')->nullable();
          $table->smallInteger('road_size_contract_d')->nullable();
          $table->smallInteger('road_type_contract_a')->nullable();
          $table->smallInteger('road_type_contract_b')->nullable();
          $table->smallInteger('road_type_contract_c')->nullable();
          $table->smallInteger('road_type_contract_d')->nullable();
          $table->smallInteger('road_type_contract_e')->nullable();
          $table->smallInteger('road_type_contract_f')->nullable();
          $table->smallInteger('road_type_contract_g')->nullable();
          $table->smallInteger('road_type_contract_h')->nullable();
          $table->smallInteger('road_type_contract_i')->nullable();
          $table->smallInteger('road_type_sub2_contract_a')->nullable();
          $table->smallInteger('road_type_sub2_contract_b')->nullable();
          $table->smallInteger('road_type_sub1_contract')->nullable();
          $table->smallInteger('road_type_sub3_contract')->nullable();
          $table->text('front_road_f')->nullable();
          $table->smallInteger('front_road_status')->nullable();
          $table->string('front_road_memo',128)->nullable();
          $table->smallInteger('contract_a')->nullable();
          $table->smallInteger('contract_b')->nullable();
          $table->smallInteger('contract_c')->nullable();
          $table->smallInteger('contract_d')->nullable();
          $table->smallInteger('contract_status')->nullable();
          $table->string('contract_memo',128)->nullable();
          $table->smallInteger('gathering_request_title')->default(1);
          $table->string('gathering_request_to',128)->nullable();
          $table->smallInteger('gathering_request_to_check')->default(1);
          $table->smallInteger('gathering_request_third_party')->nullable();
          $table->smallInteger('notices_a')->defaut(1);
          $table->smallInteger('notices_b')->nullable();
          $table->smallInteger('notices_c')->nullable();
          $table->smallInteger('notices_d')->defaut(1);
          $table->smallInteger('notices_e')->defaut(1);
          $table->smallInteger('notices_f')->defaut(1);
          $table->smallInteger('request_permission_a')->defaut(1);
          $table->smallInteger('request_permission_b')->defaut(1);
          $table->smallInteger('request_permission_c')->defaut(1);
          $table->smallInteger('request_permission_d')->defaut(1);
          $table->smallInteger('request_permission_e')->defaut(0);
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
          $table->smallInteger('desired_contract_terms_n')->nullable();
          $table->smallInteger('desired_contract_terms_o')->nullable();
          $table->smallInteger('desired_contract_terms_p')->nullable();
          $table->smallInteger('desired_contract_terms_q')->nullable();
          $table->smallInteger('desired_contract_terms_r')->nullable();
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
          $table->smallInteger('optional_items_a')->nullable();
          $table->smallInteger('optional_items_b')->nullable();
          $table->smallInteger('optional_items_c')->nullable();
          $table->smallInteger('optional_items_d')->nullable();
          $table->smallInteger('optional_items_e')->nullable();
          $table->smallInteger('optional_items_f')->nullable();
          $table->smallInteger('optional_items_g')->nullable();
          $table->smallInteger('optional_items_h')->nullable();
          $table->smallInteger('optional_items_i')->nullable();
          $table->smallInteger('optional_items_j')->nullable();
          $table->smallInteger('optional_items_k')->nullable();
          $table->string('optional_memo_content',128)->nullable();
          $table->string('desired_contract_date',128)->nullable();
          $table->string('settlement_date',128)->nullable();
          $table->dateTime('expiration_date')->nullable();
          $table->smallInteger('gathering_request_status')->nullable();
          $table->string('gathering_request_memo',128)->nullable();
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
        Schema::dropIfExists('pj_purchase_doc');
    }
}
