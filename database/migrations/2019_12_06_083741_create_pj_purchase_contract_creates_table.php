<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePjPurchaseContractCreatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_purchase_contract_creates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pj_purchase_contract_id')->nullable();
            $table->integer('broker_housebuilder_by_seller')->nullable();
            $table->integer('broker_housebuilder')->default(0);
            $table->string('building_for_merchandise',128)->nullable();
            $table->string('project_buy_building_number', 128)->nullable();
            $table->string('project_buy_building_address',128)->nullable();
            $table->text('notices_residential_contract')->nullable();
            $table->text('notices_road_contract')->nullable();
            $table->text('notices_building_contract')->nullable();
            $table->smallInteger('notices_status')->nullable();
            $table->string('notices_memo',128)->nullable();
            $table->smallInteger('property_description_product')->nullable();
            $table->smallInteger('property_description_dismantling')->nullable();
            $table->smallInteger('property_description_transfer')->default(2)->nullable();
            $table->smallInteger('property_description_removal_by_buyer')->nullable();
            $table->smallInteger('property_description_cooler_removal_by_buyer')->nullable();
            $table->smallInteger('property_description_kind')->nullable();
            $table->smallInteger('property_description_status')->nullable();
            $table->string('property_description_memo', 128)->nullable();
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
            $table->smallInteger('road_private_burden_contract')->nullable();
            $table->decimal('road_private_burden_area_contract', 16, 4)->nullable();
            $table->smallInteger('road_private_burden_share_denom_contract')->nullable();
            $table->decimal('road_private_burden_share_number_contract', 12, 0)->nullable();
            $table->smallInteger('road_private_burden_amount_contract')->nullable();
            $table->smallInteger('road_setback_area_contract')->nullable();
            $table->decimal('road_setback_area_size_contract', 16, 4)->nullable();
            $table->smallInteger('road_type_sub1_contract')->nullable();
            $table->smallInteger('road_type_sub3_contract')->nullable();
            $table->text('remarks_contract')->nullable();
            $table->smallInteger('road_private_status')->nullable();
            $table->string('road_private_memo', 128)->nullable();
            $table->smallInteger('c_article4_contract')->default(1)->nullable();
            $table->smallInteger('c_article4_sub_contract')->nullable();
            $table->string('c_article4_sub_text_contract', 128)->nullable();
            $table->decimal('c_article4_clearing_standard_area', 16, 4)->nullable();
            $table->decimal('c_article4_clearing_standard_area_cost', 12, 0)->nullable();
            $table->text('c_article4_clearing_standard_area_remarks')->nullable();
            $table->smallInteger('c_article5_fixed_survey_contract')->default(2)->nullable();
            $table->smallInteger('c_article5_fixed_survey_options_contract')->nullable();
            $table->string('c_article5_fixed_survey_options_other_contract', 128)->nullable();
            $table->smallInteger('c_article5_land_surveying')->nullable();
            $table->smallInteger('c_article5_burden_contract')->default(1)->nullable();
            $table->smallInteger('c_article5_burden2_contract')->default(1)->nullable();
            $table->timestamp('c_article5_date_contract')->nullable();
            $table->string('c_article5_creator_contract', 128)->nullable();
            $table->smallInteger('c_article6_1_contract')->nullable();
            $table->smallInteger('c_article6_2_contract')->nullable();
            $table->smallInteger('c_article8_contract')->nullable();
            $table->string('c_article8_contract_text', 128)->nullable();
            $table->smallInteger('c_article12_contract')->nullable();
            $table->string('c_article12_contract_text', 128)->nullable();
            $table->smallInteger('c_article15_contract')->nullable();
            $table->string('c_article15_loan_contract_0', 128)->nullable();
            $table->decimal('c_article15_loan_amount_contract_0', 12, 0)->nullable();
            $table->smallInteger('c_article15_loan_issue_contract_0')->nullable();
            $table->string('c_article15_loan_contract_1', 128)->nullable();
            $table->decimal('c_article15_loan_amount_contract_1', 12, 0)->nullable();
            $table->smallInteger('c_article15_loan_issue_contract_1')->nullable();
            $table->timestamp('c_article15_loan_release_date_contract')->nullable();
            $table->smallInteger('c_article16_contract')->nullable();
            $table->smallInteger('c_article16_burden_contract')->nullable();
            $table->smallInteger('c_article16_base_contract')->nullable();
            $table->smallInteger('c_article23_confirm')->default(2)->nullable();
            $table->string('c_article23_confirm_write', 128)->nullable();
            $table->string('c_article23_creator', 128)->nullable();
            $table->timestamp('c_article23_create_date')->nullable();
            $table->text('c_article23_other')->nullable();
            $table->smallInteger('front_road_a')->nullable();
            $table->smallInteger('front_road_b')->nullable();
            $table->smallInteger('front_road_c')->nullable();
            $table->smallInteger('front_road_d')->nullable();
            $table->smallInteger('front_road_e')->nullable();
            $table->smallInteger('front_road_f')->nullable();
            $table->smallInteger('front_road_g')->nullable();
            $table->smallInteger('front_road_h')->nullable();
            $table->smallInteger('front_road_i')->nullable();
            $table->smallInteger('front_road_j')->nullable();
            $table->smallInteger('front_road_k')->nullable();
            $table->smallInteger('front_road_l')->nullable();
            $table->smallInteger('agricultural_section_a')->nullable();
            $table->smallInteger('agricultural_section_b')->nullable();
            $table->smallInteger('development_permission')->nullable();
            $table->smallInteger('cross_border')->nullable();
            $table->smallInteger('trading_other_people')->nullable();
            $table->smallInteger('separate_with_pen_a')->nullable();
            $table->smallInteger('separate_with_pen_b')->nullable();
            $table->smallInteger('building_for_merchandise_a')->nullable();
            $table->smallInteger('building_for_merchandise_b')->nullable();
            $table->smallInteger('building_for_merchandise_c')->nullable();
            $table->smallInteger('profitable_property_a')->nullable();
            $table->smallInteger('profitable_property_b')->nullable();
            $table->smallInteger('remarks_other')->nullable();
            $table->string('original_contents_text_a', 128)->nullable();
            $table->string('original_contents_text_b', 128)->nullable();
            $table->smallInteger('contract_status')->nullable();
            $table->string('contract_memo', 128)->nullable();
            $table->text('display_and_remarks_of_land')->nullable();
            $table->text('build_remarks')->nullable();
            $table->smallInteger('real_estate_related_status')->nullable();
            $table->string('real_estate_related_memo', 128)->nullable();
            // seller and occupancy seller
            $table->string('seller_and_occupancy_address', 128)->nullable();
            $table->string('seller_and_occupancy_name', 128)->nullable();
            $table->text('seller_and_occupancy_remarks')->nullable();
            $table->string('seller_and_occupancy_occupation_address', 128)->nullable();
            $table->string('seller_and_occupancy_occupation_name', 128)->nullable();
            $table->text('seller_and_occupancy_occupation_matter')->nullable();
            $table->smallInteger('seller_and_occupancy_status')->nullable();
            $table->string('seller_and_occupancy_memo', 128)->nullable();

            $table->smallInteger('owner_a_land')->nullable();
            $table->string('owner_address_a_land', 128)->nullable();
            $table->string('owner_name_a_land', 128)->nullable();
            $table->smallInteger('ownership_a_land')->nullable();
            $table->text('ownership_memo_a_land')->nullable();
            $table->smallInteger('ownership_b_land')->nullable();
            $table->text('ownership_memo_b_land')->nullable();
            $table->smallInteger('owner_a_building')->nullable();
            $table->string('owner_address_a_building', 128)->nullable();
            $table->string('owner_name_a_building', 128)->nullable();
            $table->smallInteger('ownership_a_building')->nullable();
            $table->text('ownership_memo_a_building')->nullable();
            $table->smallInteger('ownership_b_building')->nullable();
            $table->text('ownership_memo_b_building')->nullable();
            $table->smallInteger('area_division')->nullable();
            $table->string('residential_land_date', 128)->nullable();
            $table->string('residential_land_number', 128)->nullable();
            $table->string('permission_date', 128)->nullable();
            $table->string('permission_number', 128)->nullable();
            $table->string('inspected_date', 128)->nullable();
            $table->string('inspected_number', 128)->nullable();
            $table->string('completion_notice_date', 128)->nullable();
            $table->string('completion_notice_number', 128)->nullable();
            $table->smallInteger('city_planning_facility')->nullable();
            $table->smallInteger('city_planning_facility_possession')->nullable();
            $table->string('city_planning_facility_possession_memo', 128)->nullable();
            $table->smallInteger('city_planning_facility_possession_road')->nullable();
            $table->string('city_planning_facility_possession_road_name', 128)->nullable();
            $table->string('city_planning_facility_possession_road_widht', 128)->nullable();
            $table->smallInteger('urban_development_business')->nullable();
            $table->string('urban_development_business_memo', 128)->nullable();
            $table->text('registration_record_building_remarks', 128)->nullable();
            $table->smallInteger('use_district')->nullable();
            $table->string('use_district_text', 128)->nullable();
            $table->smallInteger('restricted_use_district')->nullable();
            $table->string('restricted_use_district_text', 128)->nullable();
            $table->decimal('building_coverage_ratio', 16, 4)->nullable();
            $table->smallInteger('fire_prevention_area')->nullable();
            $table->string('fire_prevention_area_text', 128)->nullable();
            $table->decimal('floor_area_ratio_text', 16, 4)->nullable();
            $table->decimal('road_width', 16, 4)->nullable();
            $table->smallInteger('wall_restrictions')->nullable();
            $table->smallInteger('exterior_wall_receding')->nullable();
            $table->smallInteger('minimum_floor_area')->nullable();
            $table->decimal('minimum_floor_area_text', 16, 4)->nullable();
            $table->smallInteger('building_agreement')->nullable();
            $table->smallInteger('absolute_height_limit')->nullable();
            $table->decimal('absolute_height_limit_text', 16, 4)->nullable();
            $table->smallInteger('private_road_change_or_abolition_restrictions')->nullable();
            $table->text('building_standard_act_remarks')->nullable();
            // site and road
            $table->smallInteger('create_site_and_road_direction_0')->nullable();
            $table->smallInteger('create_site_and_road_0')->nullable();
            $table->smallInteger('create_site_and_road_type_0')->nullable();
            $table->decimal('width_0', 16, 4)->nullable();
            $table->decimal('length_of_roadway_0', 16, 4)->nullable();
            $table->smallInteger('create_site_and_road_direction_1')->nullable();
            $table->smallInteger('create_site_and_road_1')->nullable();
            $table->smallInteger('create_site_and_road_type_1')->nullable();
            $table->decimal('width_1', 16, 4)->nullable();
            $table->decimal('length_of_roadway_1', 16, 4)->nullable();
            $table->smallInteger('create_site_and_road_direction_2')->nullable();
            $table->smallInteger('create_site_and_road_2')->nullable();
            $table->smallInteger('create_site_and_road_type_2')->nullable();
            $table->decimal('width_2', 16, 4)->nullable();
            $table->decimal('length_of_roadway_2', 16, 4)->nullable();
            $table->smallInteger('create_site_and_road_direction_3')->nullable();
            $table->smallInteger('create_site_and_road_3')->nullable();
            $table->smallInteger('create_site_and_road_type_3')->nullable();
            $table->decimal('width_3', 16, 4)->nullable();
            $table->decimal('length_of_roadway_3', 16, 4)->nullable();
            $table->string('road_position_designation', 128)->nullable();
            $table->string('designated_date', 128)->nullable();
            $table->string('number', 128)->nullable();
            $table->smallInteger('setback')->nullable();
            $table->decimal('setback_area', 16, 4)->nullable();
            $table->smallInteger('restricted_ordinance')->nullable();
            $table->string('restricted_ordinance_text', 128)->nullable();
            $table->decimal('alley_part_length', 16, 4)->nullable();
            $table->decimal('alley_part_width', 16, 4)->nullable();
            $table->string('road_type_text', 128)->nullable();
            $table->text('site_and_road_text')->nullable();
            // other legal restrictions
            $table->smallInteger('provisional_land_change')->nullable();
            $table->string('provisional_land_change_text', 128)->nullable();
            $table->string('provisional_land_change_notice', 128)->nullable();
            $table->smallInteger('provisional_land_change_map')->nullable();
            $table->smallInteger('liquidation')->nullable();
            $table->smallInteger('liquidation_money')->nullable();
            $table->decimal('liquidation_money_text', 12, 0)->nullable();
            $table->smallInteger('levy')->nullable();
            $table->smallInteger('levy_money')->nullable();
            $table->decimal('levy_money_text', 12, 0)->nullable();
            $table->smallInteger('architectural_restrictions')->nullable();
            $table->text('other_legal_restrictions_text_a')->nullable();
            $table->boolean('restricted law')->nullable();
            $table->boolean('restricted_law_9')->nullable();
            $table->boolean('restricted_law_16')->nullable();
            $table->boolean('restricted_law_21')->nullable();
            $table->boolean('restricted_law_33')->nullable();
            $table->boolean('restricted_law_35')->nullable();
            $table->boolean('restricted_law_36')->nullable();
            $table->boolean('restricted_law_42')->nullable();
            $table->boolean('restricted_law_46')->nullable();
            $table->boolean('restricted_law_47')->nullable();
            $table->boolean('restricted_law_49')->nullable();
            $table->boolean('restricted_law_50')->nullable();
            $table->boolean('restricted_law_51')->nullable();
            $table->boolean('restricted_law_54')->nullable();
            $table->boolean('restricted_law_55')->nullable();
            $table->text('other_legal_restrictions_text_b')->nullable();
            $table->smallInteger('restricted_law_status')->nullable();
            $table->string('restricted_law_memo', 128)->nullable();
            // potable water
            $table->smallInteger('potable_water_facilities')->nullable();
            $table->smallInteger('potable_water_front_road_piping')->nullable();
            $table->decimal('potable_water_front_road_piping_text', 16, 4)->nullable();
            $table->smallInteger('potable_water_on_site_service_pipe')->nullable();
            $table->decimal('potable_water_on_site_service_pipe_text', 16, 4)->nullable();
            $table->smallInteger('potable_water_private_pipe')->nullable();
            $table->smallInteger('potable_water_schedule')->nullable();
            $table->smallInteger('potable_water_schedule_year')->nullable();
            $table->smallInteger('potable_water_schedule_month')->nullable();
            $table->decimal('potable_water_participation_fee', 12, 0)->nullable();
            // electrical
            $table->smallInteger('electrical_retail_company')->default(1)->nullable();
            $table->string('electrical_retail_company_name', 128)->nullable();
            $table->string('electrical_retail_company_address', 128)->nullable();
            $table->string('electrical_retail_company_contact', 128)->nullable();
            $table->smallInteger('electrical_schedule')->nullable();
            $table->smallInteger('electrical_schedule_year')->nullable();
            $table->smallInteger('electrical_schedule_month')->nullable();
            $table->decimal('electrical_charge', 12, 0)->nullable();
            // gas
            $table->smallInteger('gas_facilities')->nullable();
            $table->smallInteger('gas_front_road_piping')->nullable();
            $table->string('gas_front_road_piping_text', 128)->nullable();
            $table->smallInteger('gas_on_site_service_pipe')->nullable();
            $table->string('gas_on_site_service_pipe_text', 128)->nullable();
            $table->smallInteger('gas_private_pipe')->nullable();
            $table->smallInteger('gas_schedule')->nullable();
            $table->smallInteger('gas_schedule_year')->nullable();
            $table->smallInteger('gas_schedule_month')->nullable();
            $table->decimal('gas_charge', 12, 0)->nullable();
            // sewage
            $table->smallInteger('sewage_facilities')->nullable();
            $table->smallInteger('sewage_front_road_piping')->nullable();
            $table->string('sewage_front_road_piping_text', 128)->nullable();
            $table->smallInteger('sewage_on_site_service_pipe')->nullable();
            $table->string('sewage_on_site_service_pipe_text', 128)->nullable();
            $table->smallInteger('sewage_private_pipe')->nullable();
            $table->smallInteger('septic_tank_installation')->nullable();
            $table->smallInteger('sewage_schedule')->nullable();
            $table->smallInteger('sewage_schedule_year')->nullable();
            $table->smallInteger('sewage_schedule_month')->nullable();
            $table->decimal('sewage_charge', 12, 0)->nullable();
            // miscellaneous water
            $table->smallInteger('miscellaneous_water_facilities')->nullable();
            $table->smallInteger('miscellaneous_water_front_road_piping')->nullable();
            $table->string('miscellaneous_water_front_road_piping_text', 128)->nullable();
            $table->smallInteger('miscellaneous_water_on_site_service_pipe')->nullable();
            $table->string('miscellaneous_water_on_site_service_pipe_text', 128)->nullable();
            $table->smallInteger('miscellaneous_water_schedule')->nullable();
            $table->smallInteger('miscellaneous_water_schedule_year')->nullable();
            $table->smallInteger('miscellaneous_water_schedule_month')->nullable();
            $table->decimal('miscellaneous_water_charge', 12, 0)->nullable();
            // rain water
            $table->smallInteger('rain_water_facilities')->nullable();
            $table->smallInteger('rain_water_exclusion')->nullable();
            $table->smallInteger('rain_water_schedule')->nullable();
            $table->smallInteger('rain_water_schedule_year')->nullable();
            $table->smallInteger('rain_water_schedule_month')->nullable();
            $table->decimal('rain_water_charge', 12, 0)->nullable();
            $table->text('water_supply_and_drainage_remarks')->nullable();
            $table->smallInteger('shape_structure')->nullable();
            // earth and sand
            $table->smallInteger('earth_and_sand_vigilance')->nullable();
            $table->smallInteger('earth_and_sand_special_vigilance')->nullable();
            // performance evaluation
            $table->smallInteger('performance_evaluation')->default(1)->nullable();
            // survey status
            $table->smallInteger('survey_status_implementation')->nullable();
            $table->text('survey_status_results')->nullable();
            // maintenance
            $table->smallInteger('maintenance_confirmed_certificat')->nullable();
            $table->smallInteger('maintenance_inspection_certificate')->nullable();
            $table->smallInteger('maintenance_renovation')->nullable();
            $table->smallInteger('maintenance_renovation_confirmed_certificat')->nullable();
            $table->smallInteger('maintenance_renovation_inspection_certificate')->nullable();
            $table->smallInteger('maintenance_building_situation_survey')->nullable();
            $table->smallInteger('maintenance_building_situation_survey_report')->nullable();
            $table->smallInteger('maintenance_building_housing_performance_evaluation')->nullable();
            $table->smallInteger('maintenance_building_housing_performance_evaluation_report')->nullable();
            $table->smallInteger('maintenance_regular_survey_report')->nullable();
            $table->smallInteger('maintenance_periodic_survey_report_a')->nullable();
            $table->smallInteger('maintenance_periodic_survey_report_b')->nullable();
            $table->smallInteger('maintenance_periodic_survey_report_c')->nullable();
            $table->smallInteger('maintenance_periodic_survey_report_d')->nullable();
            $table->smallInteger('maintenance_construction_started_before')->nullable();
            // $table->smallInteger('maintenance_construction_started_before_seismic_standard_certification');
            $table->smallInteger('maintenance_construction_started_before_seismic')->nullable();
            $table->smallInteger('maintenance_construction_started_before_sub')->nullable();
            $table->string('maintenance_construction_started_before_sub_text', 128)->nullable();
            $table->text('maintenance_remarks')->nullable();
            // use asbestos
            $table->smallInteger('use_asbestos_Reference')->default(1)->nullable();
            $table->string('use_asbestos_Reference_text', 128)->nullable();
            $table->smallInteger('use_asbestos_record')->default(2)->nullable()->nullable();
            // seismic diagnosis
            $table->smallInteger('seismic_diagnosis_presence')->default(2)->nullable();
            $table->smallInteger('seismic_diagnosis_document')->nullable();
            $table->smallInteger('seismic_standard_certification')->nullable();
            $table->smallInteger('seismic_diagnosis_performance_evaluation')->nullable();
            $table->smallInteger('seismic_diagnosis_result')->nullable();
            $table->text('seismic_diagnosis_remarks')->nullable();
            // infrastructure remarks
            $table->text('infrastructure_remarks')->nullable();
            $table->smallInteger('infrastructure_status')->nullable();
            $table->string('infrastructure_memo', 128)->nullable();
            // manual release
            $table->smallInteger('manual_release')->nullable();
            // deposit conservation
            $table->smallInteger('deposit_conservation_measures')->default(1)->nullable();
            $table->smallInteger('deposit_conservation_method')->nullable();
            $table->string('deposit_conservation_period', 128)->nullable();
            // payment deposit
            $table->smallInteger('payment_deposit_measures')->default(1)->nullable();
            $table->string('payment_deposit_period', 128)->nullable();
            // liability for collateral
            $table->smallInteger('liability_for_collateral_measures')->default(1)->nullable();
            $table->string('liability_for_collateral_measures_text', 128)->nullable();
            $table->smallInteger('transaction_terms_status')->nullable();
            $table->string('transaction_terms_memo', 128)->nullable();
            // important matter
            $table->text('important_matters_text')->nullable();
            // attachment
            $table->smallInteger('attachment_district_planning')->nullable();
            $table->smallInteger('attachment_road')->nullable();
            $table->smallInteger('attachment_land_surveying_map')->nullable();
            $table->smallInteger('attachment_enomoto')->nullable();
            $table->smallInteger('attachment_public_map')->nullable();
            $table->smallInteger('attachment_gas_map')->nullable();
            $table->smallInteger('attachment_waterworks_diagram')->nullable();
            $table->smallInteger('attachment_sewer_diagram')->nullable();
            $table->smallInteger('attachment_city_planning')->nullable();
            $table->smallInteger('attachment_buried_cultural_property')->nullable();
            $table->smallInteger('attachment_road_ledger')->nullable();
            $table->smallInteger('attachment_property_tax_details')->nullable();
            $table->smallInteger('attachment_sales_contract')->nullable();
            $table->smallInteger('attachment_manual_supplementary_material')->nullable();
            $table->string('attachment_other_document_a', 128)->nullable();
            $table->string('attachment_other_document_b', 128)->nullable();
            $table->string('attachment_other_document_c', 128)->nullable();
            $table->string('attachment_other_document_d', 128)->nullable();
            $table->smallInteger('other_important_matters_document_status')->nullable();
            $table->string('other_important_matters_document_memo', 128)->nullable();
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
        Schema::dropIfExists('pj_purchase_contract_create');
    }
}
