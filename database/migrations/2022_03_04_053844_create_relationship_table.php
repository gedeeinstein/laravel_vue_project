<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// --------------------------------------------------------------------------
class CreateRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        // ------------------------------------------------------------------
        // References
        // ------------------------------------------------------------------
        $ref = (object) array(
            'id'            => 'id',
            'project'       => 'projects',
            'project_id'    => 'project_id',
            'sheet'         => 'pj_sheets',
            'sheet_id'      => 'pj_sheet_id',
            'stock'         => 'pj_stockings',
            'stock_id'      => 'pj_stocking_id',
            'stock_cost'    => 'pj_stock_cost_parents',
            'stock_cost_id' => 'pj_stock_cost_parent_id',
            'sale'          => 'pj_sales',
            'sale_id'       => 'pj_sale_id',
            'sale_plan'     => 'pj_sale_plans',
            'sale_plan_id'  => 'pj_sale_plan_id',
            'user'          => 'users',
            'user_id'       => 'user_id'
        );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        Schema::table( 'users', function( Blueprint $table ){
            $table->foreign( 'user_role_id' )->references('id')->on('user_roles')
                  ->onUpdate('cascade')->onDelete('set null');
            $table->foreign( 'company_id' )->references('id')->on('companies')
                  ->onUpdate('cascade')->onDelete('set null');
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        Schema::table( 'pj_basic_infos', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->project_id )->references( $ref->id )->on( $ref->project );
        });
        Schema::table( 'pj_basic_qa_adds', function( Blueprint $table ) use( $ref ){
            $table->foreign( 'pj_basic_qa_id' )->references( $ref->id )->on( 'pj_basic_qas' );
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        Schema::table( 'projects', function( Blueprint $table ) use( $ref ){
            $table->foreign( 'usage_area' )->references( $ref->id )->on( 'master_values' );
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        Schema::table( 'pj_basic_qas', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->project_id )->references( $ref->id )->on( $ref->project );
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        Schema::table( 'pj_memos', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->project_id )->references( $ref->id )->on( $ref->project );
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        Schema::table( 'request_inspections', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->user_id )->references( $ref->id )->on( $ref->user );
            $table->foreign( $ref->project_id )->references( $ref->id )->on( $ref->project );
        });
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Project Sheet
        // ------------------------------------------------------------------
        Schema::table( 'pj_sheets', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->project_id )->references( $ref->id )->on( $ref->project )->onDelete('cascade');
        });
        // ------------------------------------------------------------------
        Schema::table( 'pj_checklists', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->sheet_id )->references( $ref->id )->on( $ref->sheet )->onDelete('cascade');
        });
        // ------------------------------------------------------------------
        Schema::table( 'pj_sales', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->sheet_id )->references( $ref->id )->on( $ref->sheet )->onDelete('cascade');
        });
        // ------------------------------------------------------------------
        Schema::table( 'pj_sale_calculators', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->sale_id )->references( $ref->id )->on( $ref->sale )->onDelete('cascade');
        });
        // ------------------------------------------------------------------
        Schema::table( 'pj_sale_plans', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->sale_id )->references( $ref->id )->on( $ref->sale )->onDelete('cascade');
        });
        // ------------------------------------------------------------------
        Schema::table( 'pj_sale_plan_sections', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->sale_plan_id )->references( $ref->id )->on( $ref->sale_plan )->onDelete('cascade');
        });
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Project Stocking
        // ------------------------------------------------------------------
        Schema::table( 'pj_stockings', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->sheet_id )->references( $ref->id )->on( $ref->sheet )->onDelete('cascade');
        });
        // ------------------------------------------------------------------
        Schema::table( 'pj_stock_procurements', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->stock_id )->references( $ref->id )->on( $ref->stock )->onDelete('cascade');
        });
        // ------------------------------------------------------------------
        Schema::table( 'pj_stock_registers', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->stock_id )->references( $ref->id )->on( $ref->stock )->onDelete('cascade');
            $table->foreign( $ref->stock_cost_id )->references( $ref->id )->on( $ref->stock_cost )->onDelete('cascade');
        });
        // ------------------------------------------------------------------
        Schema::table( 'pj_stock_finances', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->stock_id )->references( $ref->id )->on( $ref->stock )->onDelete('cascade');
            $table->foreign( $ref->stock_cost_id )->references( $ref->id )->on( $ref->stock_cost )->onDelete('cascade');
        });
        // ------------------------------------------------------------------
        Schema::table( 'pj_stock_taxes', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->stock_id )->references( $ref->id )->on( $ref->stock )->onDelete('cascade');
            $table->foreign( $ref->stock_cost_id )->references( $ref->id )->on( $ref->stock_cost )->onDelete('cascade');
        });
        // ------------------------------------------------------------------
        Schema::table( 'pj_stock_constructions', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->stock_id )->references( $ref->id )->on( $ref->stock )->onDelete('cascade');
            $table->foreign( $ref->stock_cost_id )->references( $ref->id )->on( $ref->stock_cost )->onDelete('cascade');
        });
        // ------------------------------------------------------------------
        Schema::table( 'pj_stock_surveys', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->stock_id )->references( $ref->id )->on( $ref->stock )->onDelete('cascade');
            $table->foreign( $ref->stock_cost_id )->references( $ref->id )->on( $ref->stock_cost )->onDelete('cascade');
        });
        // ------------------------------------------------------------------
        Schema::table( 'pj_stock_others', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->stock_id )->references( $ref->id )->on( $ref->stock )->onDelete('cascade');
            $table->foreign( $ref->stock_cost_id )->references( $ref->id )->on( $ref->stock_cost )->onDelete('cascade');
        });
        // ------------------------------------------------------------------
        Schema::table( 'pj_stock_cost_rows', function( Blueprint $table ) use( $ref ){
            $table->foreign( $ref->stock_cost_id )->references( $ref->id )->on( $ref->stock_cost )->onDelete('cascade');
        });
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Properties
        // ------------------------------------------------------------------
        Schema::table('pj_lot_contractors', function (Blueprint $table) {
            $table->foreign('pj_lot_common_id')->references('id')->on('pj_lot_commons');
            $table->foreign('pj_property_owner_id')->references('id')->on('pj_property_owners');
        });
        Schema::table('pj_lot_commons', function (Blueprint $table) {
            $table->foreign('pj_lot_residential_a_id')->references('id')->on('pj_lot_residential_a')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('pj_lot_road_a_id')->references('id')->on('pj_lot_road_a')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('pj_lot_building_a_id')->references('id')->on('pj_lot_building_a')->onUpdate('cascade')->onDelete('set null');
        });

        // Purchase Sales
        Schema::table('pj_purchase_sales', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_purchase_sale_buyer_staffs', function (Blueprint $table) {
            $table->foreign('pj_purchase_sale_id')->references('id')->on('pj_purchase_sales')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_purchase_sale_pj_memos', function (Blueprint $table) {
            $table->foreign('pj_purchase_sale_id')->references('id')->on('pj_purchase_sales')->onUpdate('cascade')->onDelete('set null');
        });

        // Purchase
        Schema::table('pj_purchases', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects');
        });

        Schema::table('pj_lot_residential_purchase_creates', function (Blueprint $table) {
            $table->foreign('pj_lot_contractor_id')->references('id')->on('pj_lot_contractors')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_road_purchase_creates', function (Blueprint $table) {
            $table->foreign('pj_lot_contractor_id')->references('id')->on('pj_lot_contractors')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_building_purchase_creates', function (Blueprint $table) {
            $table->foreign('pj_lot_contractor_id')->references('id')->on('pj_lot_contractors')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('pj_purchase_targets', function( Blueprint $table ){
            $table->foreign('pj_purchase_id')->references('id')->on('pj_purchases');
        });
        Schema::table('pj_purchase_target_buildings', function (Blueprint $table) {
            $table->foreign('pj_purchase_target_id')->references('id')->on('pj_purchase_targets');
        });
        Schema::table('pj_purchase_target_contractors', function (Blueprint $table) {
            $table->foreign('pj_lot_contractor_id')->references('id')->on('pj_lot_contractors');
            $table->foreign('pj_purchase_target_id')->references('id')->on('pj_purchase_targets');
        });
        Schema::table('pj_purchase_docs', function (Blueprint $table) {
            $table->foreign('pj_purchase_target_id')->references('id')->on('pj_purchase_targets')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_purchase_doc_optional_memos', function (Blueprint $table) {
            $table->foreign('pj_purchase_doc_id')->references('id')->on('pj_purchase_docs')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_purchase_responses', function( Blueprint $table ){
            $table->foreign('pj_purchase_target_id')->references('id')->on('pj_purchase_targets')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_purchase_contracts', function( Blueprint $table ){
            $table->foreign('pj_purchase_target_id')->references('id')->on('pj_purchase_targets')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_purchase_contract_creates', function( Blueprint $table ){
            $table->foreign('pj_purchase_contract_id')->references('id')->on('pj_purchase_contracts')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_purchase_contract_create_buildings', function( Blueprint $table ){
            $table->foreign('pj_purchase_contract_create_id', 'purchase_create_id' )->references('id')->on('pj_purchase_contract_creates')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('pj_lot_building_a_id', 'purchase_building_id')->references('id')->on('pj_lot_building_a')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_purchase_contract_mediations', function (Blueprint $table) {
            $table->foreign('pj_purchase_contract_id')->references('id')->on('pj_purchase_contracts')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_purchase_contract_deposits', function (Blueprint $table) {
            $table->foreign('pj_purchase_contract_id')->references('id')->on('pj_purchase_contracts')->onUpdate('cascade')->onDelete('set null');
        });

        // Mas basic
        Schema::table('mas_basics', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('mas_basic_restrictions', function (Blueprint $table) {
            // $table->foreign('mas_basic_project_id')->references('project_id')->on('mas_basics');
            $table->foreign('mas_basic_id')->references('id')->on('mas_basics')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('mas_settings', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects');
        });

        Schema::table('mas_section_plans', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects');
        });
        Schema::table('mas_sections', function (Blueprint $table) {
            $table->foreign('mas_section_plan_id')->references('id')->on('mas_section_plans');
            $table->foreign('project_id')->references('id')->on('projects');
        });
        Schema::table('mas_section_payoffs', function (Blueprint $table) {
            $table->foreign('mas_section_id')->references('id')->on('mas_sections')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('set null');
        });

        // ----------------------------------------------------------------------
        // Master Finance Relations
        // ----------------------------------------------------------------------
        Schema::table('mas_finances', function( Blueprint $table ){
            $table->foreign('project_id')->references('id')->on('projects');
        });
        Schema::table('mas_finance_purchase_contractors', function( Blueprint $table ){
            $table->foreign('mas_finance_id', 'mas_finance_foreign')->references('id')->on('mas_finances');
            $table->foreign('pj_lot_contractor_id', 'mas_finance_contractor_foreign')->references('id')->on('pj_lot_contractors');
        });
        Schema::table('mas_finance_purchase_account_mains', function( Blueprint $table ){
            $table->foreign('mas_finance_id', 'mas_finance_accounts_foreign')->references('id')->on('mas_finances');
        });
        Schema::table('mas_finance_units', function( Blueprint $table ){
            $table->foreign('mas_finance_id')->references('id')->on('mas_finances');
        });
        Schema::table('mas_finance_unit_moneys', function( Blueprint $table ){
            $table->foreign('mas_finance_unit_id')->references('id')->on('mas_finance_units');
        });
        Schema::table('mas_finance_repayments', function( Blueprint $table ){
            $table->foreign('mas_finance_unit_id')->references('id')->on('mas_finance_units');
        });
        Schema::table('mas_finance_return_banks', function( Blueprint $table ){
            $table->foreign('mas_finance_id')->references('id')->on('mas_finances');
        });
        Schema::table('mas_finance_expenses', function( Blueprint $table ){
            $table->foreign('mas_finance_id')->references('id')->on('mas_finances');
            $table->foreign('additional_id')->references('id')->on('pj_stock_cost_rows');
        });
        // ----------------------------------------------------------------------

        // ---------------------------------------------------------------------
        // Mas Lot Relation
        // ---------------------------------------------------------------------

        // Mas Lot Residential
        // ---------------------------------------------------------------------
        Schema::table('mas_lot_residentials', function (Blueprint $table) {
            $table->foreign('pj_lot_residential_a_id')->references('id')->on('pj_lot_residential_a')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('sale_lot_residential_id')->references('id')->on('sale_lot_residentials');
        });
        Schema::table('mas_lot_residential_parcel_use_districts', function( Blueprint $table ){
            $table->foreign('mas_lot_residential_id', 'mas_lot_residential_use_districts_foreign')->references('id')->on('mas_lot_residentials')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('mas_lot_residential_parcel_build_ratios', function( Blueprint $table ){
            $table->foreign('mas_lot_residential_id', 'mas_lot_residential_build_ratios_foreign')->references('id')->on('mas_lot_residentials')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('mas_lot_residential_parcel_floor_ratios', function( Blueprint $table ){
            $table->foreign('mas_lot_residential_id', 'mas_lot_residential_floor_ratios_foreign')->references('id')->on('mas_lot_residentials')->onUpdate('cascade')->onDelete('set null');
        });
        // ---------------------------------------------------------------------

        // Mas Lot Road
        // ---------------------------------------------------------------------
        Schema::table('mas_lot_roads', function (Blueprint $table) {
            $table->foreign('pj_lot_road_a_id')->references('id')->on('pj_lot_road_a')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('sale_lot_road_id')->references('id')->on('sale_lot_roads');
        });
        Schema::table('mas_lot_road_parcel_use_districts', function( Blueprint $table ){
            $table->foreign('mas_lot_road_id', 'mas_lot_road_use_districts_foreign')->references('id')->on('mas_lot_roads')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('mas_lot_road_parcel_build_ratios', function( Blueprint $table ){
            $table->foreign('mas_lot_road_id', 'mas_lot_road_build_ratios_foreign')->references('id')->on('mas_lot_roads')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('mas_lot_road_parcel_floor_ratios', function( Blueprint $table ){
            $table->foreign('mas_lot_road_id', 'mas_lot_road_floor_ratios_foreign')->references('id')->on('mas_lot_roads')->onUpdate('cascade')->onDelete('set null');
        });
        // ---------------------------------------------------------------------

        // Mas Lot Building
        // ---------------------------------------------------------------------
        Schema::table('mas_lot_buildings', function (Blueprint $table) {
            $table->foreign('pj_lot_building_a_id')->references('id')->on('pj_lot_building_a')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('sale_lot_building_id')->references('id')->on('sale_lot_buildings');
        });
        Schema::table('mas_lot_building_floor_sizes', function (Blueprint $table) {
            $table->foreign('mas_lot_building_id')->references('id')->on('mas_lot_buildings')->onUpdate('cascade')->onDelete('set null');
        });
        // ---------------------------------------------------------------------


        // ---------------------------------------------------------------------
        // Section Sales
        // ---------------------------------------------------------------------
        Schema::table( 'section_sales', function( Blueprint $table ){
            $table->foreign( 'mas_section_id' )->references('id')->on( 'mas_sections' );
        });
        Schema::table( 'section_sale_remarks', function( Blueprint $table ){
            $table->foreign( 'section_sale_id' )->references('id')->on( 'section_sales' );
        });
        // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------
        // Section contract
        // ---------------------------------------------------------------------
        Schema::table( 'sale_contracts', function( Blueprint $table ){
            $table->foreign( 'mas_section_id' )->references('id')->on( 'mas_sections' );
        });
        Schema::table( 'sale_contract_deposits', function( Blueprint $table ){
            $table->foreign( 'sale_contract_id' )->references('id')->on( 'sale_contracts' );
        });
        Schema::table( 'sale_purchases', function( Blueprint $table ){
            $table->foreign( 'sale_contract_id' )->references('id')->on( 'sale_contracts' );
        });
        Schema::table( 'sale_product_residences', function( Blueprint $table ){
            $table->foreign( 'sale_contract_id' )->references('id')->on( 'sale_contracts' );
        });
        Schema::table( 'sale_product_residences', function( Blueprint $table ){
            $table->foreign( 'mas_lot_building_id' )->references('id')->on( 'mas_lot_buildings' );
        });
        Schema::table( 'sale_mediations', function( Blueprint $table ){
            $table->foreign( 'sale_contract_id' )->references('id')->on( 'sale_contracts' );
        });
        Schema::table( 'sale_fees', function( Blueprint $table ){
            $table->foreign( 'sale_contract_id' )->references('id')->on( 'sale_contracts' );
        });
        Schema::table( 'sale_fee_prices', function( Blueprint $table ){
            $table->foreign( 'sale_fee_id' )->references('id')->on( 'sale_fees' );
            $table->foreign( 'sale_contract_id' )->references('sale_contract_id')->on( 'sale_fees' );
        });
        // ---------------------------------------------------------------------


        // ---------------------------------------------------------------------
        // Companies
        // ---------------------------------------------------------------------
        Schema::table('companies_banks', function( Blueprint $table ){
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('companies_offices', function( Blueprint $table ){
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('companies_bank_accounts', function( Blueprint $table ){
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('bank_id')->references('id')->on('companies_banks')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('companies_borrowers', function( Blueprint $table ){
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('bank_id')->references('id')->on('companies_banks')->onUpdate('cascade')->onDelete('set null');
        });

        // other_additional_qa_checks
        Schema::table('other_additional_qa_checks', function( Blueprint $table ){
            $table->foreign('category_id')->references('id')->on('other_additional_qa_categories')->onUpdate('cascade')->onDelete('set null');
        });

        // ----------------------------------------------------------------------
        // Assist A Relation
        // ----------------------------------------------------------------------
        Schema::table('pj_properties', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects');
        });
        Schema::table('pj_property_owners', function (Blueprint $table) {
            $table->foreign('pj_property_id')->references('id')->on('pj_properties')->onUpdate('cascade')->onDelete('set null');
        });
        // Residential A
        // ----------------------------------------------------------------------
        Schema::table('pj_lot_residential_a', function (Blueprint $table) {
            $table->foreign('pj_property_id')->references('id')->on('pj_properties')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_residential_owners', function (Blueprint $table) {
            $table->foreign('pj_property_owners_id')->references('id')->on('pj_property_owners')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('pj_lot_residential_a_id')->references('id')->on('pj_lot_residential_a')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_residential_parcel_use_districts', function( Blueprint $table ){
            $table->foreign('pj_lot_residential_a_id', 'pj_lot_residential_use_districts_foreign')->references('id')->on('pj_lot_residential_a')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_residential_parcel_build_ratios', function( Blueprint $table ){
            $table->foreign('pj_lot_residential_a_id', 'pj_lot_residential_build_ratios_foreign')->references('id')->on('pj_lot_residential_a')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_residential_parcel_floor_ratios', function( Blueprint $table ){
            $table->foreign('pj_lot_residential_a_id', 'pj_lot_residential_floor_ratios_foreign')->references('id')->on('pj_lot_residential_a')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_residential_purchases', function (Blueprint $table) {
            $table->foreign('pj_lot_residential_a_id')->references('id')->on('pj_lot_residential_a')->onUpdate('cascade')->onDelete('set null');
        });
        // Road A
        // ----------------------------------------------------------------------
        Schema::table('pj_lot_road_a', function (Blueprint $table) {
            $table->foreign('pj_property_id')->references('id')->on('pj_properties')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_road_owners', function (Blueprint $table) {
            $table->foreign('pj_property_owners_id')->references('id')->on('pj_property_owners')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('pj_lot_road_a_id')->references('id')->on('pj_lot_road_a')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_road_parcel_use_districts', function( Blueprint $table ){
            $table->foreign('pj_lot_road_a_id', 'pj_lot_road_use_districts_foreign')->references('id')->on('pj_lot_road_a')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_road_parcel_build_ratios', function( Blueprint $table ){
            $table->foreign('pj_lot_road_a_id', 'pj_lot_road_build_ratios_foreign')->references('id')->on('pj_lot_road_a')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_road_parcel_floor_ratios', function( Blueprint $table ){
            $table->foreign('pj_lot_road_a_id', 'pj_lot_road_floor_ratios_foreign')->references('id')->on('pj_lot_road_a')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_road_purchases', function (Blueprint $table) {
            $table->foreign('pj_lot_road_a_id')->references('id')->on('pj_lot_road_a')->onUpdate('cascade')->onDelete('set null');
        });
        // Building A
        // ----------------------------------------------------------------------
        Schema::table('pj_lot_building_a', function (Blueprint $table) {
            $table->foreign('pj_property_id')->references('id')->on('pj_properties')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_building_floor_sizes', function( Blueprint $table ){
            $table->foreign('pj_lot_building_a_id')->references('id')->on('pj_lot_building_a')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_building_owners', function (Blueprint $table) {
            $table->foreign('pj_property_owners_id')->references('id')->on('pj_property_owners')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('pj_lot_building_a_id')->references('id')->on('pj_lot_building_a')->onUpdate('cascade')->onDelete('set null');
        });
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Assist B Relation
        // ----------------------------------------------------------------------
        Schema::table('pj_property_restrictions', function (Blueprint $table) {
            $table->foreign('pj_property_id')->references('id')->on('pj_properties')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_residential_a', function (Blueprint $table) {
            $table->foreign('pj_lot_residential_b_id')->references('id')->on('pj_lot_residential_b')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('pj_lot_road_a', function (Blueprint $table) {
            $table->foreign('pj_lot_road_b_id')->references('id')->on('pj_lot_road_b')->onUpdate('cascade')->onDelete('set null');
        });
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Project Expense Relation
        // ----------------------------------------------------------------------
        Schema::table('pj_expenses', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects');
        });
        Schema::table('pj_expense_rows', function (Blueprint $table) {
            $table->foreign('pj_expense_id')->references('id')->on('pj_expenses');
            $table->foreign('additional_id')->references('id')->on('pj_stock_cost_rows');
        });
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Trading Ledger Relation
        // ----------------------------------------------------------------------
        Schema::table('trading_ledgers', function (Blueprint $table) {
            $table->foreign('pj_purchase_target_id')->references('id')->on('pj_purchase_targets');
            $table->foreign('pj_purchase_sale_id')->references('id')->on('pj_purchase_sales');
            $table->foreign('sale_contract_id')->references('id')->on('sale_contracts');
        });
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Mas Memos Relation
        // ----------------------------------------------------------------------
        Schema::table('mas_memos', function (Blueprint $table) {
            $table->foreign('mas_section_id')->references('id')->on('mas_sections');
            $table->foreign('author')->references('id')->on('users');
        });
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // Mas Memos Relation
        // ----------------------------------------------------------------------
        Schema::table('sale_memos', function (Blueprint $table) {
            $table->foreign('mas_section_id')->references('id')->on('mas_sections');
            $table->foreign('author')->references('id')->on('users');
        });
        // ----------------------------------------------------------------------

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relationship');
    }
}
