<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Project;
// --------------------------------------------------------------------------
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\Company;
use App\Models\User;
use App\Models\MasterValue;
use App\Models\MasterRegion;
use App\Models\PjLotContractor;
use App\Models\PjPurchaseTarget;
use App\Models\PjPurchaseContract;
use App\Models\PjPurchaseContractCreate;
use App\Models\PjPurchaseContractDetail;
use App\Models\PjPurchaseContractDeposit;
use App\Models\PjPurchaseContractMediation;
use App\Models\CompanyBankAccount;
use App\Models\PjPurchaseContractCreateBuilding;
// --------------------------------------------------------------------------
use App\Models\PjPurchase;
use App\Models\PjPurchaseTargetBuilding;
use App\Models\PjPurchaseTargetContractor;
use App\Models\PjProperty;
use App\Models\PjPropertyOwner;
use App\Models\PjLotResidentialA;
use App\Models\PjLotResidentialOwner;
use App\Models\PjLotRoadA;
use App\Models\PjLotRoadOwner;
use App\Models\PjLotBuildingA;
use App\Models\PjLotBuildingOwner;
use App\Models\PjBuildingFloorSize;
use App\Models\PjLotCommon;
use App\Models\RequestInspection as Inspection;
// --------------------------------------------------------------------------
use App\Reports\PurchaseContractCreateReport;
use App\Reports\PurchaseContractCreateImportReport;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class ProjectPurchaseContractCreateController extends Controller {
    public function __construct()
    {
      $this->company              = new Company;
      $this->master_value         = new MasterValue;
      $this->master_region        = new MasterRegion;
      $this->contractor           = new PjLotContractor;
      $this->purchase_target      = new PjPurchaseTarget;
      $this->project              = new Project();
      $this->property             = new PjProperty();
      $this->property_owners      = new PjPropertyOwner();
      $this->residentials_a       = new PjLotResidentialA();
      $this->roads_a              = new PjLotRoadA();
      $this->buildings_a          = new PjLotBuildingA();
      $this->residential_owners   = new PjLotResidentialOwner();
      $this->road_owners          = new PjLotRoadOwner();
      $this->building_owners      = new PjLotBuildingOwner();
      $this->building_floor_size  = new PjBuildingFloorSize();
      $this->purchase             = new PjPurchase();
      $this->common               = new PjLotCommon();
      $this->purchase_target_building     = new PjPurchaseTargetBuilding();
      $this->purchase_target_contractor   = new PjPurchaseTargetContractor();
    }

    // ----------------------------------------------------------------------
    // Update handler
    // ----------------------------------------------------------------------
    public function update( $projectID, $targetID, Request $request ){

        // ------------------------------------------------------------------
        $alert = new \stdClass; $response = new \stdClass;
        $data = new \stdClass; $response->data = $data;
        $response->alert = $alert; $response->status = 'error';
        // ------------------------------------------------------------------
        $alert->icon = 'error'; $alert->heading = __('label.error');
        $alert->text = __('label.failed_update_message');
        // ------------------------------------------------------------------
        // return;

        // ------------------------------------------------------------------
        try {
            // --------------------------------------------------------------
            // Find the purchase target and contract, if empty return
            // --------------------------------------------------------------
            $target = PjPurchaseTarget::with('contract')->find( $targetID );
            if( !$target || empty( $target->contract )){
                return response()->json( (array) $response );
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Assign the model if ID exists, otherwise create new
            // --------------------------------------------------------------
            $model = new PjPurchaseContractCreate;
            $entry = (object) $request->entry;
            if( !empty( $entry->id )){
                $model = PjPurchaseContractCreate::find( $entry->id );
                if( !$model ) $model = new PjPurchaseContractCreate;
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Assign foreign contract ID
            // --------------------------------------------------------------
            if( !empty( $target->contract->id )){
                $model->pj_purchase_contract_id = $target->contract->id;
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get contract data updates
            // --------------------------------------------------------------
            $contract = $this->get_contract_updates( $entry );
            foreach( $contract as $prop => $update ){
                $model->{$prop} = $update;
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get important data updates
            // --------------------------------------------------------------
            $import = $this->get_important_updates( $entry );
            foreach( $import as $prop => $update ){
                $model->{$prop} = $update;
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $model->save(); // Save the updates
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // update purchase contract mediations data
            // --------------------------------------------------------------
            foreach ($request->purchase_contract_mediations as $key => $mediation) {
                $mediation = PjPurchaseContractMediation::updateOrCreate( ['id' => $mediation['id']], $mediation );
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Update contract-create product building data
            // --------------------------------------------------------------
            if( !empty( $request->buildings )) foreach( $request->buildings as $building ){
                $building = (object) $building;
                // ----------------------------------------------------------
                if( !empty( $building->product_building )){
                    $dataset = (object) $building->product_building;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    $fields = collect([
                        'pj_lot_building_a_id', 'house_number',
                        'building_number', 'building_parcel', 'building_address'
                    ]);
                    // ------------------------------------------------------
                    $building = new PjPurchaseContractCreateBuilding();
                    if( !empty( $dataset->id )){
                        $building = PjPurchaseContractCreateBuilding::find( $dataset->id );
                        if( !$building ) $building = new PjPurchaseContractCreateBuilding();
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    $fields->each( function( $field ) use( $dataset, $building ){
                        if( property_exists( $dataset, $field )) $building->{$field} = $dataset->{$field};
                    });
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    $building->pj_purchase_contract_create_id = $model->id; // Update contract-create ID
                    $building->save();
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $relations = collect([
                'property.buildings' => function( $query ){
                    $query->where( 'exists_building_residential', true );
                    $query->orderBy( 'created_at', 'desc' );
                    $query->with('productBuilding');
                }
                // ----------------------------------------------------------
            ]);
            // --------------------------------------------------------------
            $response->project = Project::with( $relations->all())->find( $projectID );
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            $updates = collect( $contract )->merge( $import );
            $response->updates = $updates->all();
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $response->status = 'success'; // Update the alert
            $alert->icon = 'success'; $alert->heading = __('label.success');
            $alert->text = __('label.success_update_message');
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            return response()->json( (array) $response );
            // --------------------------------------------------------------
        } catch( \Exception $error ){
            // --------------------------------------------------------------
            // error response
            // --------------------------------------------------------------
            Log::error([
                'message' => $error->getMessage(),
                'file'    => $error->getFile().'         : '.$error->getLine(),
                'route'   => $_SERVER['REQUEST_METHOD'].': '.$_SERVER['REQUEST_URI'],
            ]);
            // --------------------------------------------------------------
            $alert->error = $error->getMessage();
            return response()->json( $response , 500 );
            // --------------------------------------------------------------
        }
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Contract create page
    // ----------------------------------------------------------------------
    public function index( Request $request ){
        $data = new \stdClass;
        $data->user = Auth::user();
        $data->users = User::all()->keyBy('id');        
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $relations = collect([
            // --------------------------------------------------------------
            'purchaseSale', 'property',
            'property.roads', 'property.residentials.common.contractors',
            // --------------------------------------------------------------
            'property.buildings' => function( $query ){
                $query->where( 'exists_building_residential', true );
                $query->orderBy( 'created_at', 'desc' );
                $query->with('productBuilding');
            },
            // --------------------------------------------------------------
            'purchase.targets.contract'
            // --------------------------------------------------------------
        ]);
        // ------------------------------------------------------------------
        $project = Project::with( $relations->all())->find( $request->project );
        if( !$project ) return abort(404);
        $data->project = $project;
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // New data template
        // ------------------------------------------------------------------
        $data->new = new \stdClass;
        $data->new->create = factory(  PjPurchaseContractCreate::class )->states('init')->make();
        $data->new->building = factory( PjPurchaseContractCreateBuilding::class )->state('init')->make();
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        $data->page_title = "仕入契約作成::{$project->title}";
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Project inspection request
        // ------------------------------------------------------------------
        $query = Inspection::with([ 'user' ])
            ->where( 'kind', 3 )
            ->where( 'active', true )
            ->where( 'project_id', $project->id )
            ->orderBy( 'created_at', 'desc' );
        $data->inspection = $query->first();
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Find the purchase-target, contract and contract-create data
        // ------------------------------------------------------------------
        $relations = array(
            'doc', 'contract.create.buildings',
            'contract.purchase_contract_mediations', 'buildings', 'response'
        );
        $target = PjPurchaseTarget::with( $relations )->find( $request->target );
        if( empty( $target ) || empty( $target->contract )) return abort(404);
        // ------------------------------------------------------------------
        $data->target = $target;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // get residential and road data
        // ------------------------------------------------------------------
        $data->master_value = $this->master_value::all()->keyBy('id');
        $data->residentials = $this->residentials_a::where('pj_property_id', $project->property->id ?? null)->with(
            'use_districts',
            'build_ratios',
            'floor_ratios')->get();
        $data->roads = $this->roads_a::where('pj_property_id', $project->property->id ?? null)->with(
            'use_districts',
            'build_ratios',
            'floor_ratios')->get();
        // ------------------------------------------------------------------

        // -----------------------------------------------------------------
        // set purcahase number
        // -----------------------------------------------------------------
        $purchase_targets = $data->target->purchase->purchase_targets;
        foreach ($purchase_targets as $key => $target) {
            if ($data->target->id == $target->id) {
                $data->target['purchase_number'] = $key + 1;
            }
        }
        // -----------------------------------------------------------------

        // ------------------------------------------------------------------
        // get contractor and owner data
        // ------------------------------------------------------------------
        $purchase_target_contractors = $data->target->purchase_target_contractors;
        $purchase_target_contractors_group_by_name = collect([]);
        // ------------------------------------------------------------------
        foreach ($purchase_target_contractors as $key => $purchase_target_contractor) {
            $same_property = collect([]);

            // get the same contractor's name in pj lot contractor
            $same_contractors = $this->contractor::where('name', $purchase_target_contractor->contractor->name)->get();
            foreach ($same_contractors as $key => $same_contractor) {

                // check if pj_lot_contractor.property_id = purchase_target_contractor.property_id
                if ($same_contractor->common->pj_property_id == $purchase_target_contractor->contractor->common->pj_property_id) {
                    $same_property->push($same_contractor);
                }
            }
            $purchase_target_contractors_group_by_name->push($same_property);
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        $contractors_name = collect([]);
        $lot_owners = collect([]);
        // ------------------------------------------------------------------
        foreach ($purchase_target_contractors_group_by_name as $k => $lot_contractors) {
            $rowspan = 0; //rowspan
            foreach ($lot_contractors as $key => $lot_contractor) {

                // -------------------------------------------------------------
                // assign residential, road and building data
                // -------------------------------------------------------------
                $lot_contractor['residential'] = $lot_contractor->common->residential_a;
                $lot_contractor['road']        = $lot_contractor->common->road_a;
                $lot_contractor['building']    = $lot_contractor->common->building_a;
                // -------------------------------------------------------------

                // -------------------------------------------------------------
                // rowspan calculation for purchase information block
                // -------------------------------------------------------------
                if ($lot_contractor->common->residential_a) $rowspan++;
                if ($lot_contractor->common->road_a) $rowspan++;
                if ($lot_contractor->common->building_a) $rowspan++;
                // -------------------------------------------------------------

                // ----------------------------------------------------------
                // collect contractor's name and property owner from residential, road and building
                // ----------------------------------------------------------
                if ($lot_contractor->common->residential_a) {
                    $contractors_name->push($lot_contractor->name);
                    $lot_owners->push($lot_contractor->common->residential_a->residential_owners->load('property_owner'));
                }
                // ----------------------------------------------------------
                if ($lot_contractor->common->road_a) {
                    $contractors_name->push($lot_contractor->name);
                    $lot_owners->push($lot_contractor->common->road_a->road_owners->load('property_owner'));
                }
                // ----------------------------------------------------------
                if ($lot_contractor->common->building_a) {
                    $contractors_name->push($lot_contractor->name);
                    $lot_owners->push($lot_contractor->common->building_a->building_owners->load('property_owner'));
                }
              // ------------------------------------------------------------
            }
            $lot_contractors['rowspan'] = $rowspan;
        }
        $data->purchase_target_contractors_group_by_name = $purchase_target_contractors_group_by_name;
        $data->lot_kinds = ['residential', 'road', 'building'];
        // -----------------------------------------------------------------
        // flattens a multi-dimensional collection into a single dimension
        // and get property owner name
        // -----------------------------------------------------------------
        $properties_owner = $lot_owners->flatten()->map(function ($lot_owner, $key) {
            return $lot_owner->property_owner->name;
        });
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        // get unique contractor and property owner name
        // -----------------------------------------------------------------
        $data->contractors_and_owners_name = [
            'contractors_name' => $contractors_name->unique(),
            'properties_owner' => $properties_owner->unique(),
            'different_name' => array_diff($contractors_name->toArray(), $properties_owner->toArray()),
        ];
        // -----------------------------------------------------------------

        $data->companies = $this->company::all()->keyBy('id'); // get all company data
        $data->companies->load('users');
        // ------------------------------------------------------------------
        // flattens a multi-dimensional collection into a single dimension
        // and get property owner name
        // ------------------------------------------------------------------
        $properties_owner = $lot_owners->flatten()->map(function ($lot_owner, $key) {
            return $lot_owner->property_owner->name;
        });
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // get unique contractor and property owner name
        // ------------------------------------------------------------------
        $data->contractors_and_owners_name = [
            'contractors_name' => $contractors_name->unique(),
            'properties_owner' => $properties_owner->unique(),
            'different_name' => array_diff($contractors_name->toArray(), $properties_owner->toArray()),
        ];
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // get master data
        // ------------------------------------------------------------------
        $data->parcel_city              = $this->master_region::pluck('name', 'id');
        $data->building_usetype         = $this->master_value::where('type', 'building_usetype')->pluck('value', 'id');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return view('backend.project.purchase-contract-create.form', (array) $data );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Get contract updates
    // ----------------------------------------------------------------------
    private function get_contract_updates( $dataset ){
        // ------------------------------------------------------------------
        $updates = new \stdClass;
        $dataset = (object) $dataset;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $fields = collect([
            // --------------------------------------------------------------
            'project_buy_building_number', 'project_buy_building_address',
            'notices_residential_contract', 'notices_road_contract',
            'notices_building_contract', 'notices_status', 'notices_memo',
            // --------------------------------------------------------------
            'property_description_status', 'property_description_memo',
            // --------------------------------------------------------------
            'road_size_contract_a', 'road_size_contract_b',
            'road_size_contract_c', 'road_size_contract_d',
            // --------------------------------------------------------------
            'road_type_contract_a', 'road_type_contract_b', 'road_type_contract_c',
            'road_type_contract_d', 'road_type_contract_e', 'road_type_contract_f',
            'road_type_contract_g', 'road_type_contract_h', 'road_type_contract_i',
            // --------------------------------------------------------------
            'road_type_sub1_contract', 'road_type_sub2_contract_b',
            'remarks_contract', 'road_private_status', 'road_private_memo',
            // --------------------------------------------------------------
            'c_article4_contract',
            'c_article5_fixed_survey_contract',
            'c_article6_1_contract', 'c_article6_2_contract',
            'c_article8_contract',
            'c_article12_contract',
            // --------------------------------------------------------------
            'front_road_a', 'front_road_b', 'front_road_c', 'front_road_d',
            'front_road_e', 'front_road_f', 'front_road_g', 'front_road_h',
            'front_road_i', 'front_road_j', 'front_road_k', 'front_road_l',
            // --------------------------------------------------------------
            'agricultural_section_a', 'agricultural_section_b',
            'development_permission', 'cross_border', 'trading_other_people',
            'separate_with_pen_a', 'separate_with_pen_b',
            'building_for_merchandise_a', 'building_for_merchandise_b', 'building_for_merchandise_c',
            'profitable_property_a', 'profitable_property_b', 'remarks_other',
            // --------------------------------------------------------------
            'original_contents_text_a', 'original_contents_text_b',
            'contract_status', 'contract_memo',
            // --------------------------------------------------------------
        ]);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Property section
        // https://bit.ly/35JveCU
        // ------------------------------------------------------------------
        $type = 'property_description_product';
        if( !empty( $dataset->{$type} )){
            $fields->push( $type );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Demolition
            // --------------------------------------------------------------
            if( 2 === $dataset->{$type} || 4 === $dataset->{$type} ){
                // ----------------------------------------------------------
                $demolition = 'property_description_dismantling';
                $fields->push( $demolition );
                // ----------------------------------------------------------
                if( !empty( $dataset->{$demolition} ) && 2 === $dataset->{$demolition} ){
                    $fields->push( 'property_description_transfer' );
                    $fields->push( 'property_description_removal_by_buyer' );
                    $fields->push( 'property_description_cooler_removal_by_buyer' );
                }
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Building type
            // --------------------------------------------------------------
            if( 3 === $dataset->{$type} || 4 === $dataset->{$type} ){
                $fields->push( 'property_description_kind' );
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Private road
        // https://bit.ly/35ShkPa
        // ------------------------------------------------------------------
        $private = 'road_type_sub2_contract_b';
        if( !empty( $dataset->{$private} )){
            $fields->push( $private );
            // --------------------------------------------------------------
            $contract = 'road_private_burden_contract';
            $fields->push( $contract );
            // --------------------------------------------------------------
            if( !empty( $dataset->{$contract} ) && 2 === (int) $dataset->{$contract} ){
                $fields->push( 'road_private_burden_area_contract' );
                $fields->push( 'road_private_burden_share_denom_contract' );
                $fields->push( 'road_private_burden_share_number_contract' );
                $fields->push( 'road_private_burden_amount_contract' );
            }
            // --------------------------------------------------------------
            $setback = 'road_setback_area_contract';
            $fields->push( $setback );
            // --------------------------------------------------------------
            if( !empty( $dataset->{$setback} ) && 1 === (int) $dataset->{$setback} ){
                $fields->push( 'road_setback_area_size_contract' );
            }
            // --------------------------------------------------------------
            $fields->push( 'road_type_sub3_contract' );
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Article 4
        // https://bit.ly/3dqlu32
        // ------------------------------------------------------------------
        $contract = 'c_article4_contract';
        if( !empty( $dataset->{$contract} )){
            $fields->push( $contract );
            // --------------------------------------------------------------
            if( 2 === (int) $dataset->{$contract} ){
                // ----------------------------------------------------------
                $subContract = 'c_article4_sub_contract';
                $fields->push( $subContract );
                // ----------------------------------------------------------
                if( !empty( $dataset->{$subContract} ) && 3 === (int) $dataset->{$subContract} ){
                    $fields->push( 'c_article4_sub_text_contract' );
                }
                // ----------------------------------------------------------
                $fields->push( 'c_article4_clearing_standard_area' );
                $fields->push( 'c_article4_clearing_standard_area_cost' );
                $fields->push( 'c_article4_clearing_standard_area_remarks' );
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Article 5
        // https://bit.ly/2Wm1Hwl
        // ------------------------------------------------------------------
        $contract = 'c_article5_fixed_survey_contract';
        if( !empty( $dataset->{$contract} )){
            $fields->push( $contract );
            // --------------------------------------------------------------
            if( 3 === (int) $dataset->{$contract} ){
                // ----------------------------------------------------------
                $option = 'c_article5_fixed_survey_options_contract';
                $fields->push( $option );
                // ----------------------------------------------------------
                if( 5 === (int) $dataset->{$option} ){
                    $fields->push( 'c_article5_fixed_survey_options_other_contract' );
                }
                // ----------------------------------------------------------
                elseif( 2 === (int) $dataset->{$option} ){
                    $fields->push( 'c_article5_land_surveying' );
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
            elseif( 2 === (int) $dataset->{$contract} ){
                $fields->push( 'c_article5_burden_contract' );
                $fields->push( 'c_article5_burden2_contract' );
            }
            // --------------------------------------------------------------
            elseif( 1 === (int) $dataset->{$contract} ){
                $fields->push( 'c_article5_date_contract' );
                $fields->push( 'c_article5_creator_contract' );
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Article 8
        // https://bit.ly/3dz5A6P
        // ------------------------------------------------------------------
        $contract = 'c_article8_contract';
        if( !empty( $dataset->{$contract} )){
            $fields->push( $contract );
            if( 4 === (int) $dataset->{$contract} ){
                $fields->push( 'c_article8_contract_text' );
            }
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Article 12
        // https://bit.ly/3bi2nH9
        // ------------------------------------------------------------------
        $contract = 'c_article12_contract';
        if( !empty( $dataset->{$contract} )){
            $fields->push( $contract );
            if( 3 === (int) $dataset->{$contract} ){
                $fields->push( 'c_article12_contract_text' );
            }
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Article 15
        // https://bit.ly/35LnMap
        // ------------------------------------------------------------------
        $contract = 'c_article15_contract';
        if( !empty( $dataset->{$contract} )){
            $fields->push( $contract );
            if( 1 === (int) $dataset->{$contract} ){
                $fields->push( 'c_article15_loan_contract_0' );
                $fields->push( 'c_article15_loan_amount_contract_0' );
                $fields->push( 'c_article15_loan_issue_contract_0' );
                $fields->push( 'c_article15_loan_contract_1' );
                $fields->push( 'c_article15_loan_amount_contract_1' );
                $fields->push( 'c_article15_loan_issue_contract_1' );
                $fields->push( 'c_article15_loan_release_date_contract' );
            }
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Article 16
        // https://bit.ly/2SSsEWd
        // ------------------------------------------------------------------
        $contract = 'c_article16_contract';
        if( !empty( $dataset->{$contract} )){
            $fields->push( $contract );
            if( 1 === (int) $dataset->{$contract} ){
                // ----------------------------------------------------------
                $burden = 'c_article16_burden_contract';
                $fields->push( $burden );
                // ----------------------------------------------------------
                if( !empty( $dataset->{$burden}) && 3 === $dataset->{$burden}){
                    $fields->push( 'c_article16_base_contract' );
                }
                // ----------------------------------------------------------
            }
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Article 23
        // https://bit.ly/2ztyupV
        // ------------------------------------------------------------------
        $contract = 'c_article23_confirm';
        if( !empty( $dataset->{$contract})){
            $fields->push( $contract );
            if( 1 === (int) $dataset->{$contract}){
                $fields->push( 'c_article23_confirm_write' );
                $fields->push( 'c_article23_creator' );
                $fields->push( 'c_article23_create_date' );
                $fields->push( 'c_article23_other' );
            }
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Assign updates
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        // ------------------------------------------------------------------
        $fields->each( function( $field ) use( $updates, $dataset ){
            if( property_exists( $dataset, $field )) $updates->{$field} = $dataset->{$field};
        });
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Merge with default values
        // ------------------------------------------------------------------
        $default = factory( PjPurchaseContractCreate::class )->states('init-contract')->make();
        $updates = collect( $default->toArray())->merge( $updates );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return $updates->all();
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Get important updates
    // ----------------------------------------------------------------------
    private function get_important_updates( $dataset ){
        // ------------------------------------------------------------------
        $updates = new \stdClass;
        $dataset = (object) $dataset;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $fields = collect([
            'broker_housebuilder_by_seller',
            'display_and_remarks_of_land', 'build_remarks', 'real_estate_related_status',
            'real_estate_related_memo', 'seller_and_occupancy_address', 'seller_and_occupancy_name',
            'seller_and_occupancy_remarks', 'seller_and_occupancy_occupation_address', 'seller_and_occupancy_occupation_name',
            'seller_and_occupancy_occupation_matter', 'seller_and_occupancy_status', 'seller_and_occupancy_memo',
            'owner_a_land', 'owner_address_a_land', 'owner_name_a_land',
            'ownership_a_land', 'ownership_memo_a_land', 'ownership_b_land',
            'ownership_memo_b_land', 'owner_a_building', 'owner_address_a_building',
            'owner_name_a_building', 'ownership_a_building', 'ownership_memo_a_building',
            'ownership_b_building', 'ownership_memo_b_building', 'area_division',
            'residential_land_date', 'residential_land_number', 'permission_date',
            'permission_number', 'inspected_date', 'inspected_number',
            'completion_notice_date', 'completion_notice_number', 'city_planning_facility',
            'city_planning_facility_possession', 'city_planning_facility_possession_memo', 'city_planning_facility_possession_road',
            'city_planning_facility_possession_road_name', 'city_planning_facility_possession_road_widht', 'urban_development_business',
            'urban_development_business_memo', 'registration_record_building_remarks', 'use_district',
            'use_district_text', 'estricted_use_district', 'estricted_use_district_text',
            'building_coverage_ratio', 'fire_prevention_area', 'fire_prevention_area_text',
            'floor_area_ratio_text', 'road_width', 'wall_restrictions',
            'exterior_wall_receding', 'minimum_floor_area', 'minimum_floor_area_text',
            'building_agreement', 'absolute_height_limit', 'absolute_height_limit_text',
            'private_road_change_or_abolition_restrictions', 'building_standard_act_remarks', 'create_site_and_road_direction_0',
            'create_site_and_road_0', 'create_site_and_road_type_0', 'width_0',
            'length_of_roadway_0', 'create_site_and_road_direction_1', 'create_site_and_road_1',
            'create_site_and_road_type_1', 'width_1', 'length_of_roadway_1',
            'create_site_and_road_direction_2', 'create_site_and_road_2', 'create_site_and_road_type_2',
            'width_2', 'length_of_roadway_2', 'create_site_and_road_direction_3',
            'create_site_and_road_3', 'create_site_and_road_type_3', 'width_3',
            'length_of_roadway_3', 'road_position_designation', 'designated_date',
            'number', 'setback', 'setback_area',
            'restricted_ordinance', 'restricted_ordinance_text', 'alley_part_length',
            'alley_part_width', 'road_type_text', 'site_and_road_text',
            'provisional_land_change', 'provisional_land_change_text', 'provisional_land_change_notice',
            'provisional_land_change_map', 'liquidation', 'liquidation_money',
            'liquidation_money_text', 'levy', 'levy_money',
            'levy_money_text', 'architectural_restrictions', 'other_legal_restrictions_text_a',
            'restricted law', 'restricted_law_9', 'restricted_law_16',
            'restricted_law_21', 'restricted_law_33', 'restricted_law_35',
            'restricted_law_36', 'restricted_law_42', 'restricted_law_46',
            'restricted_law_47', 'restricted_law_49', 'restricted_law_50',
            'restricted_law_51', 'restricted_law_54', 'restricted_law_55',
            'other_legal_restrictions_text_b', 'restricted_law_status', 'restricted_law_memo',
            'potable_water_facilities', 'potable_water_front_road_piping', 'potable_water_front_road_piping_text',
            'potable_water_on_site_service_pipe', 'potable_water_on_site_service_pipe_text', 'potable_water_private_pipe',
            'potable_water_schedule', 'potable_water_schedule_year', 'potable_water_schedule_month',
            'potable_water_participation_fee', 'electrical_retail_company', 'electrical_retail_company_name',
            'electrical_retail_company_address', 'electrical_retail_company_contact', 'electrical_schedule',
            'electrical_schedule_year', 'electrical_schedule_month', 'electrical_charge',
            'gas_facilities', 'gas_front_road_piping', 'gas_front_road_piping_text',
            'gas_on_site_service_pipe', 'gas_on_site_service_pipe_text', 'gas_private_pipe',
            'gas_schedule', 'gas_schedule_year', 'gas_schedule_month',
            'gas_charge', 'sewage_facilities', 'sewage_front_road_piping',
            'sewage_front_road_piping_text', 'sewage_on_site_service_pipe', 'sewage_on_site_service_pipe_text',
            'sewage_private_pipe', 'septic_tank_installation', 'sewage_schedule',
            'sewage_schedule_year', 'sewage_schedule_month', 'sewage_charge',
            'miscellaneous_water_facilities', 'miscellaneous_water_front_road_piping', 'miscellaneous_water_front_road_piping_text',
            'miscellaneous_water_on_site_service_pipe', 'miscellaneous_water_on_site_service_pipe_text', 'miscellaneous_water_schedule',
            'miscellaneous_water_schedule_year', 'miscellaneous_water_schedule_month', 'miscellaneous_water_charge',
            'rain_water_facilities', 'rain_water_exclusion', 'rain_water_schedule',
            'rain_water_schedule_year', 'rain_water_schedule_month', 'rain_water_charge',
            'water_supply_and_drainage_remarks', 'shape_structure', 'earth_and_sand_vigilance',
            'earth_and_sand_special_vigilance', 'performance_evaluation', 'survey_status_implementation',
            'survey_status_results', 'maintenance_confirmed_certificat', 'maintenance_inspection_certificate',
            'maintenance_renovation', 'maintenance_renovation_confirmed_certificat', 'maintenance_renovation_inspection_certificate',
            'maintenance_building_situation_survey', 'maintenance_building_situation_survey_report', 'maintenance_building_housing_performance_evaluation',
            'maintenance_building_housing_performance_evaluation_report', 'maintenance_regular_survey_report', 'maintenance_periodic_survey_report_a',
            'maintenance_periodic_survey_report_b', 'maintenance_periodic_survey_report_c', 'maintenance_periodic_survey_report_d',
            'maintenance_construction_started_before',             'maintenance_construction_started_before_seismic', 'maintenance_construction_started_before_sub',
            'maintenance_construction_started_before_sub_text', 'maintenance_remarks', 'use_asbestos_Reference',
            'use_asbestos_Reference_text', 'use_asbestos_record', 'seismic_diagnosis_presence',
            'seismic_diagnosis_document', 'seismic_standard_certification', 'seismic_diagnosis_performance_evaluation',
            'seismic_diagnosis_result', 'seismic_diagnosis_remarks', 'infrastructure_remarks',
            'infrastructure_status', 'infrastructure_memo', 'manual_release',
            'deposit_conservation_measures', 'deposit_conservation_method', 'deposit_conservation_period',
            'payment_deposit_measures', 'payment_deposit_period', 'liability_for_collateral_measures',
            'liability_for_collateral_measures_text', 'transaction_terms_status', 'transaction_terms_memo',
            'important_matters_text', 'attachment_district_planning', 'attachment_road',
            'attachment_land_surveying_map', 'attachment_enomoto', 'attachment_public_map',
            'attachment_gas_map', 'attachment_waterworks_diagram', 'attachment_sewer_diagram',
            'attachment_city_planning', 'attachment_buried_cultural_property', 'attachment_road_ledger',
            'attachment_property_tax_details', 'attachment_sales_contract', 'attachment_manual_supplementary_material',
            'attachment_other_document_a', 'attachment_other_document_b', 'attachment_other_document_c',
            'attachment_other_document_d', 'other_important_matters_document_status', 'other_important_matters_document_memo',
        ]);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // directly related section
        // ------------------------------------------------------------------
        $directly = 'owner_a_land';
        if( !empty( $dataset->{$directly} )){
            $fields->push( $directly );
            // --------------------------------------------------------------
            if( 2 === $dataset->{$directly} ){
                // ----------------------------------------------------------
                $fields->push( 'owner_address_a_land' );
                $fields->push( 'owner_name_a_land' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        $directly = 'ownership_a_land';
        if( !empty( $dataset->{$directly} )){
            $fields->push( $directly );
            // --------------------------------------------------------------
            if( 1 === $dataset->{$directly} ){
                // ----------------------------------------------------------
                $fields->push( 'ownership_memo_a_land' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }

        $directly = 'owner_a_building';
        if( !empty( $dataset->{$directly} )){
            $fields->push( $directly );
            // --------------------------------------------------------------
            if( 2 === $dataset->{$directly} ){
                // ----------------------------------------------------------
                $fields->push( 'owner_address_a_building' );
                $fields->push( 'owner_name_a_building' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        $directly = 'ownership_a_building';
        if( !empty( $dataset->{$directly} )){
            $fields->push( $directly );
            // --------------------------------------------------------------
            if( 1 === $dataset->{$directly} ){
                // ----------------------------------------------------------
                $fields->push( 'ownership_memo_a_building' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }

        // ------------------------------------------------------------------
        // city planning
        // ------------------------------------------------------------------
        $city = 'city_planning_facility_possession';
        if( !empty( $dataset->{$city} )){
            $fields->push( $city );
            // --------------------------------------------------------------
            if( 1 === $dataset->{$city} ){
                // ----------------------------------------------------------
                $fields->push( 'city_planning_facility_possession' );
                $facility = 'city_planning_facility_possession';
                if ($dataset->{$facility}) {
                    $fields->push( $facility );
                    if (1 === $dataset->{$facility}) {
                        $fields->push( 'city_planning_facility_possession_road' );
                    }
                    if (2 === $dataset->{$facility}) {
                        $fields->push( 'city_planning_facility_possession_memo' );
                        $fields->push( 'city_planning_facility_possession_road_name' );
                        $fields->push( 'city_planning_facility_possession_road_widht' );
                    }
                }
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }

        $city = 'urban_development_business';
        if( !empty( $dataset->{$city} )){
            $fields->push( $city );
            // --------------------------------------------------------------
            if( 2 === $dataset->{$city} ){
                // ----------------------------------------------------------
                $fields->push( 'urban_development_business_memo' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // building standard act
        // ------------------------------------------------------------------
        $district = 'use_district';
        if( !empty( $dataset->{$district} )){
            $fields->push( $district );
            // --------------------------------------------------------------
            if( 1 == $dataset->{$district} ){
                // ----------------------------------------------------------
                $fields->push( 'use_district_text' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        $district = 'restricted_use_district';
        if( !empty( $dataset->{$district} )){
            $fields->push( $district );
            // --------------------------------------------------------------
            if( 1 == $dataset->{$district} ){
                // ----------------------------------------------------------
                $fields->push( 'restricted_use_district_text' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }
        // ---------------------------------------------------------------------
        $district = 'fire_prevention_area';
        if( !empty( $dataset->{$district} )){
            $fields->push( $district );
            // --------------------------------------------------------------
            if( 5 === $dataset->{$district} ){
                // ----------------------------------------------------------
                $fields->push( 'fire_prevention_area_text' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }
        // ---------------------------------------------------------------------
        $district = 'minimum_floor_area';
        if( !empty( $dataset->{$district} )){
            $fields->push( $district );
            // --------------------------------------------------------------
            if( 5 === $dataset->{$district} ){
                // ----------------------------------------------------------
                $fields->push( 'minimum_floor_area_text' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }


        // ------------------------------------------------------------------
        // site and road
        // ------------------------------------------------------------------
        $road = 'create_site_and_road_type_0';
        if( !empty( $dataset->{$road} )){
            $fields->push( $road );
            // --------------------------------------------------------------
            if( 5 === $dataset->{$road} ){
                // ----------------------------------------------------------
                $fields->push( 'width_0' );
                $fields->push( 'length_of_roadway_0' );
                $fields->push( 'road_position_designation' );
                $fields->push( 'designated_date' );
                $fields->push( 'number' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
            // --------------------------------------------------------------
            if( 7 === $dataset->{$road} ){
                // ----------------------------------------------------------
                $fields->push( 'road_type_text' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        $road = 'create_site_and_road_type_1';
        if( !empty( $dataset->{$road} )){
            $fields->push( $road );
            // --------------------------------------------------------------
            if( 5 === $dataset->{$road} ){
                // ----------------------------------------------------------
                $fields->push( 'width_1' );
                $fields->push( 'length_of_roadway_1' );
                $fields->push( 'road_position_designation' );
                $fields->push( 'designated_date' );
                $fields->push( 'number' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
            // --------------------------------------------------------------
            if( 7 === $dataset->{$road} ){
                // ----------------------------------------------------------
                $fields->push( 'road_type_text' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        $road = 'create_site_and_road_type_2';
        if( !empty( $dataset->{$road} )){
            $fields->push( $road );
            // --------------------------------------------------------------
            if( 5 === $dataset->{$road} ){
                // ----------------------------------------------------------
                $fields->push( 'width_2' );
                $fields->push( 'length_of_roadway_2' );
                $fields->push( 'road_position_designation' );
                $fields->push( 'designated_date' );
                $fields->push( 'number' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
            // --------------------------------------------------------------
            if( 7 === $dataset->{$road} ){
                // ----------------------------------------------------------
                $fields->push( 'road_type_text' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        $road = 'create_site_and_road_type_3';
        if( !empty( $dataset->{$road} )){
            $fields->push( $road );
            // --------------------------------------------------------------
            if( 5 === $dataset->{$road} ){
                // ----------------------------------------------------------
                $fields->push( 'width_3' );
                $fields->push( 'length_of_roadway_3' );
                $fields->push( 'road_position_designation' );
                $fields->push( 'designated_date' );
                $fields->push( 'number' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
            // --------------------------------------------------------------
            if( 7 === $dataset->{$road} ){
                // ----------------------------------------------------------
                $fields->push( 'road_type_text' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        $road = 'setback';
        if( !empty( $dataset->{$road} )){
            $fields->push( $road );
            // --------------------------------------------------------------
            if( 2 === $dataset->{$road} ){
                // ----------------------------------------------------------
                $fields->push( 'setback_area' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        $road = 'restricted_ordinance';
        if( !empty( $dataset->{$road} )){
            $fields->push( $road );
            // --------------------------------------------------------------
            if( 2 === $dataset->{$road} ){
                // ----------------------------------------------------------
                $fields->push( 'restricted_ordinance_text' );
                $fields->push( 'alley_part_length' );
                $fields->push( 'alley_part_width' );
                // ----------------------------------------------------------
            };
            // --------------------------------------------------------------
        }

        // ------------------------------------------------------------------
        // drinking water
        // ------------------------------------------------------------------
        $water = 'potable_water_front_road_piping';
        if( !empty( $dataset->{$water} )){
            $fields->push( $water );
            if( 1 === $dataset->{$water} ){
                $fields->push( 'potable_water_front_road_piping_text' );
            }
        }
        // ------------------------------------------------------------------
        $water = 'potable_water_on_site_service_pipe';
        if( !empty( $dataset->{$water} )){
            $fields->push( $water );
            if( 1 === $dataset->{$water} ){
                $fields->push( 'potable_water_on_site_service_pipe_text' );
            }
        }
        // ------------------------------------------------------------------
        $water = 'potable_water_schedule';
        if( !empty( $dataset->{$water} )){
            $fields->push( $water );
            if( 2 === $dataset->{$water} ){
                $fields->push( 'potable_water_schedule_year' );
                $fields->push( 'potable_water_schedule_month' );
            }
        }


        // ------------------------------------------------------------------
        // electrical
        // ------------------------------------------------------------------
        $electrical = 'electrical_retail_company';
        if( !empty( $dataset->{$electrical} )){
            $fields->push( $electrical );
            if( 2 === $dataset->{$electrical} ){
                $fields->push( 'electrical_retail_company_name' );
                $fields->push( 'electrical_retail_company_address' );
                $fields->push( 'electrical_retail_company_contact' );
            }
        }
        // ------------------------------------------------------------------
        $electrical = 'electrical_schedule';
        if( !empty( $dataset->{$electrical} )){
            $fields->push( $electrical );
            if( 2 === $dataset->{$electrical} ){
                $fields->push( 'electrical_schedule_year' );
                $fields->push( 'electrical_schedule_month' );
            }
        }


        // ------------------------------------------------------------------
        // gas
        // https://bit.ly/35LnMap
        // ------------------------------------------------------------------
        $gas = 'gas_front_road_piping';
        if( !empty( $dataset->{$gas} )){
            $fields->push( $gas );
            if( 1 === $dataset->{$gas} ){
                $fields->push( 'gas_front_road_piping_text' );
            }
        }
        // ------------------------------------------------------------------
        $gas = 'gas_on_site_service_pipe';
        if( !empty( $dataset->{$gas} )){
            $fields->push( $gas );
            if( 1 === $dataset->{$gas} ){
                $fields->push( 'gas_on_site_service_pipe_text' );
            }
        }
        // ------------------------------------------------------------------
        $gas = 'gas_facilities';
        if( !empty( $dataset->{$gas} )){
            $fields->push( $gas );
            if( 2 === $dataset->{$gas} || 3 === $dataset->{$gas} ){
                $fields->push( 'gas_private_pipe' );
            }
        }
        // ------------------------------------------------------------------
        $gas = 'gas_schedule';
        if( !empty( $dataset->{$gas} )){
            $fields->push( $gas );
            if( 2 === $dataset->{$gas} ){
                $fields->push( 'gas_schedule_year' );
                $fields->push( 'gas_schedule_month' );
            }
        }


        // ------------------------------------------------------------------
        // sewage
        // ------------------------------------------------------------------
        $sewage = 'sewage_front_road_piping';
        if( !empty( $dataset->{$sewage} )){
            $fields->push( $sewage );
            if( 1 === $dataset->{$sewage} ){
                // ----------------------------------------------------------
                $fields->push( 'sewage_front_road_piping_text' );
                // ----------------------------------------------------------
            }
        }
        // ------------------------------------------------------------------
        $sewage = 'sewage_on_site_service_pipe';
        if( !empty( $dataset->{$sewage} )){
            $fields->push( $sewage );
            if( 1 === $dataset->{$sewage} ){
                // ----------------------------------------------------------
                $fields->push( 'sewage_on_site_service_pipe_text' );
                // ----------------------------------------------------------
            }
        }
        // ------------------------------------------------------------------
        $sewage = 'sewage_facilities';
        if( !empty( $dataset->{$sewage} )){
            $fields->push( $sewage );
            if( 2 === $dataset->{$sewage} ){
                // ----------------------------------------------------------
                $fields->push( 'septic_tank_installation' );
                // ----------------------------------------------------------
            }
            if( 1 !== $dataset->{$sewage} ){
                // ----------------------------------------------------------
                $fields->push( 'sewage_private_pipe' );
                // ----------------------------------------------------------
            }
        }
        // ------------------------------------------------------------------
        $sewage = 'sewage_schedule';
        if( !empty( $dataset->{$sewage} )){
            $fields->push( $sewage );
            if( 2 === $dataset->{$sewage} ){
                // ----------------------------------------------------------
                $fields->push( 'sewage_schedule_year' );
                $fields->push( 'sewage_schedule_month' );
                // ----------------------------------------------------------
            }
        }


        // ------------------------------------------------------------------
        // miscellaneous
        // ------------------------------------------------------------------
        $miscellaneous = 'miscellaneous_water_front_road_piping';
        if( !empty( $dataset->{$miscellaneous})){
            $fields->push( $miscellaneous );
            if( 1 === $dataset->{$miscellaneous}){
                $fields->push( 'miscellaneous_water_front_road_piping_text' );
            }
        }
        // ------------------------------------------------------------------
        $miscellaneous = 'miscellaneous_water_on_site_service_pipe';
        if( !empty( $dataset->{$miscellaneous})){
            $fields->push( $miscellaneous );
            if( 1 === $dataset->{$miscellaneous}){
                $fields->push( 'miscellaneous_water_on_site_service_pipe_text' );
            }
        }
        // ------------------------------------------------------------------
        $miscellaneous = 'miscellaneous_water_schedule';
        if( !empty( $dataset->{$miscellaneous})){
            $fields->push( $miscellaneous );
            if( 2 === $dataset->{$miscellaneous}){
                $fields->push( 'miscellaneous_water_schedule_year' );
                $fields->push( 'miscellaneous_water_schedule_month' );
            }
        }


        // ------------------------------------------------------------------
        // rain
        // ------------------------------------------------------------------
        $rain = 'rain_water_schedule';
        if( !empty( $dataset->{$rain})){
            $fields->push( $rain );
            if( 2 === $dataset->{$rain}){
                $fields->push( 'rain_water_schedule_year' );
                $fields->push( 'rain_water_schedule_month' );
            }
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // rain
        // ------------------------------------------------------------------
        $contract = 'rain_water_schedule';
        if( !empty( $dataset->{$contract})){
            $fields->push( $contract );
            if( 1 === $dataset->{$contract}){
                $fields->push( 'rain_water_schedule_year' );
                $fields->push( 'rain_water_schedule_month' );
            }
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // survey
        // ------------------------------------------------------------------
        $survey = 'survey_status_implementation';
        if( !empty( $dataset->{$survey})){
            $fields->push( $survey );
            if( 1 === $dataset->{$survey}){
                $fields->push( 'survey_status_results' );
            }
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // maintenance
        // ------------------------------------------------------------------
        $maintenance = 'maintenance_renovation';
        if( !empty( $dataset->{$maintenance})){
            $fields->push( $maintenance );
            if( 1 === $dataset->{$maintenance}){
                $fields->push( 'maintenance_renovation_confirmed_certificat' );
                $fields->push( 'maintenance_renovation_inspection_certificate' );
            }
        }
        // ------------------------------------------------------------------
        $maintenance = 'maintenance_building_situation_survey';
        if( !empty( $dataset->{$maintenance})){
            $fields->push( $maintenance );
            if( 1 === $dataset->{$maintenance}){
                $fields->push( 'maintenance_building_situation_survey_report' );
            }
        }
        // ------------------------------------------------------------------
        $maintenance = 'maintenance_building_housing_performance_evaluation';
        if( !empty( $dataset->{$maintenance})){
            $fields->push( $maintenance );
            if( 1 === $dataset->{$maintenance}){
                $fields->push( 'maintenance_building_housing_performance_evaluation_report' );
            }
        }
        // ------------------------------------------------------------------
        $maintenance = 'maintenance_regular_survey_report';
        if( !empty( $dataset->{$maintenance})){
            $fields->push( $maintenance );
            if( 1 === $dataset->{$maintenance}){
                $fields->push( 'maintenance_periodic_survey_report_a' );
                $fields->push( 'maintenance_periodic_survey_report_b' );
                $fields->push( 'maintenance_periodic_survey_report_c' );
                $fields->push( 'maintenance_periodic_survey_report_d' );
            }
        }
        // ------------------------------------------------------------------
        $maintenance = 'maintenance_construction_started_before';
        if( !empty( $dataset->{$maintenance})){
            $fields->push( $maintenance );
            if( 1 === $dataset->{$maintenance}){
                $fields->push( 'maintenance_construction_started_before_seismic_standard_certification' );
            }
        }
        // ------------------------------------------------------------------
        $maintenance = 'maintenance_construction_started_before_sub';
        if( !empty( $dataset->{$maintenance})){
            $fields->push( $maintenance );
            if( 1 === $dataset->{$maintenance}){
                $fields->push( 'maintenance_construction_started_before_sub_text' );
            }
        }


        // ------------------------------------------------------------------
        // Assign updates
        // ------------------------------------------------------------------
        $fields = $fields->unique()->values(); // Remove duplicates
        // ------------------------------------------------------------------
        $fields->each( function( $field ) use( $updates, $dataset ){
            if( property_exists( $dataset, $field )) $updates->{$field} = $dataset->{$field};
        });
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Merge with default values
        // ------------------------------------------------------------------
        $default = factory( PjPurchaseContractCreate::class )->states('init-important')->make();
        $updates = collect( $default->toArray())->merge( $updates );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return $updates->all();
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Handle report request
    // ----------------------------------------------------------------------
    public function report( Request $request ){
        // ------------------------------------------------------------------
        $data = null;
        $response = (object) array( 'status' => 'success' );
        // ------------------------------------------------------------------
        $projectID = (int) $request->project;
        $targetID = (int) $request->target;

        // Get project data
        // ------------------------------------------------------------------
        $relations = collect([
            // --------------------------------------------------------------
            'purchaseSale', 'property',
            'property.roads', 'property.residentials',
            'property.residentials.common', 'property.residentials.common.contractors',
            // --------------------------------------------------------------
            'property.buildings' => function( $query ){
                $query->where( 'exists_building_residential', true );
                $query->orderBy( 'created_at', 'desc' );
            }
            // --------------------------------------------------------------
        ]);
        // ------------------------------------------------------------------
        $project = Project::with( $relations->all())->find( $projectID );

        // Find the purchase-target data
        // ------------------------------------------------------------------
        $relations = array( 'doc', 'contract', 'contract.create', 'buildings', 'response' );
        $target = PjPurchaseTarget::with( $relations )->find( $targetID );
        // ------------------------------------------------------------------
        if( !empty( $target->contract->create )){
            $data = $target->contract->create; // This should be the main data
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        if( !empty( $request->report )){
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Generate contract report
            // --------------------------------------------------------------
            if( 'contract' == $request->report ){
                // ----------------------------------------------------------
                $filePath = PurchaseContractCreateReport::reportPurchaseContractCreate($data, $projectID, $targetID);
                $response->report = $filePath;
                $response->filename = basename( $filePath );
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Generate important-notes report
            // --------------------------------------------------------------
            elseif( 'notes' == $request->report ){
                // ----------------------------------------------------------
                $filepath = PurchaseContractCreateImportReport::purchaseContractCreateImport($data, $projectID, $targetID);
                $response->report = $filepath;
                $response->filename = "重要事項説明書".basename( $filepath );
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $response->type = $request->report;
        return response()->json( (array) $response );
    }
}
