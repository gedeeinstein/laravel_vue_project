<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Project;
// --------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// --------------------------------------------------------------------------
use App\Models\MasterValue;
use App\Models\MasterRegion;
use App\Models\Project;
use App\Models\PjProperty;
use App\Models\PjPropertyOwner;
// --------------------------------------------------------------------------
use App\Models\PjLotResidentialA;
use App\Models\PjLotResidentialParcelUseDistrict;
use App\Models\PjLotResidentialParcelBuildRatio;
use App\Models\PjLotResidentialParcelFloorRatio;
use App\Models\PjLotResidentialOwner;
// --------------------------------------------------------------------------
use App\Models\PjLotRoadA;
use App\Models\PjLotRoadParcelUseDistrict;
use App\Models\PjLotRoadParcelBuildRatio;
use App\Models\PjLotRoadParcelFloorRatio;
use App\Models\PjLotRoadOwner;
// --------------------------------------------------------------------------
use App\Models\PjLotBuildingA;
use App\Models\PjLotBuildingOwner;
use App\Models\PjBuildingFloorSize;
use App\Models\StatCheck;
use Illuminate\Support\Facades\Log;
// --------------------------------------------------------------------------
use App\Models\PjLotResidentialB;
use App\Models\PjLotRoadB;
use App\Models\PjPropertyRestriction;
// --------------------------------------------------------------------------
use App\Models\PjPurchaseTargetContractor;
use App\Models\PjLotContractor;
use App\Models\PjLotCommon;

class ProjectAssistController extends Controller {

    // ----------------------------------------------------------------------
    // Define base data
    // ----------------------------------------------------------------------
    private $project;
    private $property;
    private $property_owners;
    private $residentials_a;
    private $roads_a;
    private $buildings_a;
    private $stat_check;
    private $residentials_b;
    private $roads_b;

    // ----------------------------------------------------------------------
    // Construct scoped value
    // ----------------------------------------------------------------------
    public function __construct(){
        $this->project = new Project();
        $this->property = new PjProperty();
        $this->property_owners = new PjPropertyOwner();
        $this->residentials_a = new PjLotResidentialA();
        $this->roads_a = new PjLotRoadA();
        $this->buildings_a = new PjLotBuildingA();
        $this->stat_check = new StatCheck();
        $this->residentials_b = new PjLotResidentialB();
        $this->roads_b = new PjLotRoadB();
    }

    // ----------------------------------------------------------------------
    // Project Assist A Page
    // ----------------------------------------------------------------------
    public function assist_a($id){
        // get related data from database
        // ------------------------------------------------------------------
        $user_role = auth()->user()->user_role->name;
        $project = $this->project::findOrFail($id);
        $property = $this->property::where('project_id', $id)->with('owners')->first();
        // ------------------------------------------------------------------
        $residentials = $this->residentials_a::where('pj_property_id', $property->id ?? null)->with(
            'use_districts',
            'build_ratios',
            'floor_ratios',
            'residential_owners')->get();
        $roads = $this->roads_a::where('pj_property_id', $property->id ?? null)->with(
            'use_districts',
            'build_ratios',
            'floor_ratios',
            'road_owners')->get();
        $buildings = $this->buildings_a::where('pj_property_id', $property->id ?? null)->with(
            'building_floors',
            'building_owners')->get();
        $stat_check = $this->stat_check::where('project_id', $project->id)->where('screen', 'pj_assist_a')->first();
        // ------------------------------------------------------------------

        // assign data
        // ------------------------------------------------------------------
        $data = new \stdClass;
        $data->master_values = MasterValue::select('id','type','value')->where('masterdeleted', 0)->get();
        $data->master_regions = MasterRegion::select('id','name','type')->where('type', 'city')->where('is_enable', 1)->where('prefecture_code', '04')->get();
        // ------------------------------------------------------------------
        $data->project = $project;
        $data->property = $property;
        $data->residentials = $residentials;
        $data->roads = $roads;
        $data->buildings = $buildings;
        $data->stat_check = $stat_check;
        // ------------------------------------------------------------------
        $data->page_title = "アシストA::{$project->title}";
        $data->editable   = $user_role == 'accounting_firm' ? false : true;
        $data->update_url = route('project.assist.a.update', $id);
        $data->delete_url = new \stdClass;
        $data->delete_url->property_owners = route('project.assist.a.delete', [
            'project' => $id,
            'type'    => 'property_owners'
        ]);
        $data->delete_url->residential = route('project.assist.a.delete', [
            'project' => $id,
            'type'    => 'residential'
        ]);
        $data->delete_url->road = route('project.assist.a.delete', [
            'project' => $id,
            'type'    => 'road'
        ]);
        $data->delete_url->building = route('project.assist.a.delete', [
            'project' => $id,
            'type'    => 'building'
        ]);
        $data->delete_url->section = route('project.assist.a.delete', [
            'project' => $id,
            'type'    => 'section'
        ]);
        // ------------------------------------------------------------------

        // return data assist a to view
        // ------------------------------------------------------------------
        return view('backend.project.assist-a.assist-a', (array)$data);
    }

    // ----------------------------------------------------------------------
    // Project Assist A Update Handler
    // ----------------------------------------------------------------------
    public function assist_a_update($id, Request $request){
        try {
            // --------------------------------------------------------------
            $project_id = $id;
            $property = $this->property::find($request->property['id']);
            $property_owners = $this->property_owners;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // STATUS CHECK
            // --------------------------------------------------------------
            // create or update stat_check
            $data_status = $request->stat_check;
            $data_status['project_id'] = $project_id;
            $stat_check = $this->stat_check->updateOrCreate([
                'project_id' => $project_id,
                'screen'     => 'pj_assist_a'
            ], $data_status );
            // --------------------------------------------------------------
            // check status -> if db not changes and status is 1 prevent saving data
            $status_changed = $stat_check->getChanges();
            $status_created = $stat_check->wasRecentlyCreated;
            if (!isset($status_changed['status']) && $stat_check->status == 1 && !$status_created) {
                return response()->json([
                    'status'  => 'warning',
                    'message' => '「完」の場合編集できません。'
                ]);
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // LAND SECTION
            // --------------------------------------------------------------
            // create or update property
            $data_property = $request->property;
            $data_property['project_id'] = $project_id;
            $property = $this->property->updateOrCreate([
                'id'         => $data_property['id'],
                'project_id' => $project_id
            ], $data_property );
            // --------------------------------------------------------------

            // create or update property owners
            $owners_id = [];
            foreach ($request->property_owners as $owner) {
                if (isset($owner['name'])) {
                    $owner['pj_property_id'] = $property->id;
                    $property_owners = $property_owners->updateOrCreate([
                        'id'             => $owner['id'],
                        'pj_property_id' => $property->id
                    ], $owner );
                    // request_id => saved_id
                    $owners_id += [ $owner['id'] => $property_owners->id ];
                }
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // RESIDENTIAL SECTION
            // --------------------------------------------------------------
            $residential = $this->residentials_a;
            $residentials_use_district = new PjLotResidentialParcelUseDistrict();
            $residentials_build_ratio = new PjLotResidentialParcelBuildRatio();
            $residentials_floor_ratio = new PjLotResidentialParcelFloorRatio();
            $residentials_owners = new PjLotResidentialOwner();
            // --------------------------------------------------------------
            foreach ($request->residentials as $resident) {
                // check if residential section is active
                if($resident['exists_land_residential'] == 1){

                    // create basic residentials_b before create residentials_a
                    $residentials_b = $this->residentials_b::firstOrCreate([
                        'id' => $resident['pj_lot_residential_b_id']
                    ],[]);

                    // update pj_lot_residential_a data
                    // ------------------------------------------------------
                    $residential_update = $resident;
                    unset($residential_update['use_districts']);
                    unset($residential_update['build_ratios']);
                    unset($residential_update['floor_ratios']);
                    unset($residential_update['residential_owners']);
                    // ------------------------------------------------------
                    $residential_update['pj_lot_residential_b_id'] = $residentials_b->id;
                    $residential = $residential->updateOrCreate([
                        'id'             => $resident['id'],
                        'pj_property_id' => $property->id
                    ], $residential_update );
                    // ------------------------------------------------------

                    // update pj_lot_residential_parcel_use_district
                    // ------------------------------------------------------
                    foreach ($resident['use_districts'] as $use_district) {
                        $use_district['pj_lot_residential_a_id'] = $residential->id;
                        // update data
                        // --------------------------------------------------
                        if (empty($use_district['deleted'])) {
                            $residentials_use_district->updateOrCreate([
                                'id'                      => $use_district['id'],
                                'pj_lot_residential_a_id' => $residential->id
                            ], $use_district );
                        }
                        // delete data
                        // --------------------------------------------------
                        else {
                            $delete_use_district = PjLotResidentialParcelUseDistrict::findOrFail($use_district['id']);
                            $delete_use_district->delete();
                        }
                    }
                    // ------------------------------------------------------

                    // update pj_lot_residential_parcel_build_ratio
                    // ------------------------------------------------------
                    foreach ($resident['build_ratios'] as $build_ratio) {
                        $build_ratio['pj_lot_residential_a_id'] = $residential->id;
                        // update data
                        // --------------------------------------------------
                        if (empty($build_ratio['deleted'])) {
                            $residentials_build_ratio->updateOrCreate([
                                'id'                      => $build_ratio['id'],
                                'pj_lot_residential_a_id' => $residential->id
                            ], $build_ratio );
                        }
                        // delete data
                        // --------------------------------------------------
                        else{
                            $delete_build_ratio = PjLotResidentialParcelBuildRatio::findOrFail($build_ratio['id']);
                            $delete_build_ratio->delete();
                        }
                    }
                    // ------------------------------------------------------

                    // update pj_lot_residential_parcel_build_ratio
                    // ------------------------------------------------------
                    foreach ($resident['floor_ratios'] as $floor_ratio) {
                        $floor_ratio['pj_lot_residential_a_id'] = $residential->id;
                        // update data
                        // --------------------------------------------------
                        if (empty($floor_ratio['deleted'])) {
                            $residentials_floor_ratio->updateOrCreate([
                                'id'                      => $floor_ratio['id'],
                                'pj_lot_residential_a_id' => $residential->id
                            ], $floor_ratio );
                        }
                        // delete data
                        // --------------------------------------------------
                        else{
                            $delete_floor_ratio = PjLotResidentialParcelFloorRatio::findOrFail($floor_ratio['id']);
                            $delete_floor_ratio->delete();
                        }
                    }
                    // ------------------------------------------------------

                    // update pj_lot_residential_owners
                    // ------------------------------------------------------
                    foreach ($resident['residential_owners'] as $residential_owner) {
                        $residential_owner['pj_lot_residential_a_id'] = $residential->id;
                        $residential_owner['pj_property_owners_id'] = $owners_id[$residential_owner['pj_property_owners_id']];
                        // update data
                        // --------------------------------------------------
                        if (empty($residential_owner['deleted'])) {
                            $residentials_owners->updateOrCreate([
                                'id'                      => $residential_owner['id'],
                                'pj_lot_residential_a_id' => $residential->id
                            ], $residential_owner );
                        }
                        // delete data
                        // --------------------------------------------------
                        else{
                            $delete_residential_owner = PjLotResidentialOwner::findOrFail($residential_owner['id']);
                            $delete_residential_owner->delete();
                        }
                    }
                    // ------------------------------------------------------
                }
            }

            // --------------------------------------------------------------
            // ROAD SECTION
            // --------------------------------------------------------------
            $road = $this->roads_a;
            $roads_use_district = new PjLotRoadParcelUseDistrict();
            $roads_build_ratio = new PjLotRoadParcelBuildRatio();
            $roads_floor_ratio = new PjLotRoadParcelFloorRatio();
            $roads_owners = new PjLotRoadOwner();
            // --------------------------------------------------------------
            foreach ($request->roads as $road_data) {
                // check if road section is active
                if($road_data['exists_road_residential'] == 1){

                    // create basic residentials_b before create residentials_a
                    $roads_b = $this->roads_b::firstOrCreate([
                        'id' => $road_data['pj_lot_road_b_id']
                    ],[]);

                    // update pj_lot_road_a data
                    // ------------------------------------------------------
                    $road_update = $road_data;
                    unset($road_update['use_districts']);
                    unset($road_update['build_ratios']);
                    unset($road_update['floor_ratios']);
                    unset($road_update['road_owners']);
                    // ------------------------------------------------------
                    $road_update['pj_lot_road_b_id'] = $roads_b->id;
                    $road = $road->updateOrCreate([
                        'id'             => $road_data['id'],
                        'pj_property_id' => $property->id
                    ], $road_update );
                    // ------------------------------------------------------

                    // update pj_lot_road_parcel_use_district
                    // ------------------------------------------------------
                    foreach ($road_data['use_districts'] as $use_district) {
                        $use_district['pj_lot_road_a_id'] = $road->id;
                        // update data
                        // --------------------------------------------------
                        if (empty($use_district['deleted'])) {
                            $roads_use_district->updateOrCreate([
                                'id'               => $use_district['id'],
                                'pj_lot_road_a_id' => $road->id
                            ], $use_district );
                        }
                        // delete data
                        // --------------------------------------------------
                        else{
                            $delete_use_district = PjLotRoadParcelUseDistrict::findOrFail($use_district['id']);
                            $delete_use_district->delete();
                        }
                    }
                    // ------------------------------------------------------

                    // update pj_lot_road_parcel_build_ratio
                    // ------------------------------------------------------
                    foreach ($road_data['build_ratios'] as $build_ratio) {
                        $build_ratio['pj_lot_road_a_id'] = $road->id;
                        // update data
                        // --------------------------------------------------
                        if (empty($build_ratio['deleted'])) {
                            $roads_build_ratio->updateOrCreate([
                                'id'               => $build_ratio['id'],
                                'pj_lot_road_a_id' => $road->id
                            ], $build_ratio );
                        }
                        // delete data
                        // --------------------------------------------------
                        else{
                            $delete_build_ratio = PjLotRoadParcelBuildRatio::findOrFail($build_ratio['id']);
                            $delete_build_ratio->delete();
                        }
                    }
                    // ------------------------------------------------------

                    // update pj_lot_road_parcel_build_ratio
                    // ------------------------------------------------------
                    foreach ($road_data['floor_ratios'] as $floor_ratio) {
                        $floor_ratio['pj_lot_road_a_id'] = $road->id;
                        // update data
                        // --------------------------------------------------
                        if (empty($floor_ratio['deleted'])) {
                            $roads_floor_ratio->updateOrCreate([
                                'id'               => $floor_ratio['id'],
                                'pj_lot_road_a_id' => $road->id
                            ], $floor_ratio );
                        }
                        // delete data
                        // --------------------------------------------------
                        else{
                            $delete_floor_ratio = PjLotRoadParcelFloorRatio::findOrFail($floor_ratio['id']);
                            $delete_floor_ratio->delete();
                        }
                    }
                    // ------------------------------------------------------

                    // update pj_lot_road_owners
                    // ------------------------------------------------------
                    foreach ($road_data['road_owners'] as $road_owner) {
                        $road_owner['pj_lot_road_a_id'] = $road->id;
                        $road_owner['pj_property_owners_id'] = $owners_id[$road_owner['pj_property_owners_id']];
                        // update data
                        // --------------------------------------------------
                        if (empty($road_owner['deleted'])) {
                            $roads_owners->updateOrCreate([
                                'id'               => $road_owner['id'],
                                'pj_lot_road_a_id' => $road->id
                            ], $road_owner );
                        }
                        // delete data
                        // --------------------------------------------------
                        else{
                            $delete_road_owner = PjLotRoadOwner::findOrFail($road_owner['id']);
                            $delete_road_owner->delete();
                        }

                    }
                    // ------------------------------------------------------
                }
            }

            // --------------------------------------------------------------
            // BUILDING SECTION
            // --------------------------------------------------------------
            $building = $this->buildings_a;
            $building_owners = new PjLotBuildingOwner();
            $floor_sizes = new PjBuildingFloorSize();
            // --------------------------------------------------------------
            foreach ($request->buildings as $building_data) {
                // check if building section is active
                if($building_data['exists_building_residential'] == 1){

                    // update pj_lot_building_a data
                    // ------------------------------------------------------
                    $building_update = $building_data;
                    unset($building_update['building_floors']);
                    unset($building_update['building_owners']);
                    $building_update['building_date_year'] = $building_update['building_date_western'] ?? null;
                    // ------------------------------------------------------
                    $building = $building->updateOrCreate([
                        'id'             => $building_data['id'],
                        'pj_property_id' => $property->id
                    ], $building_update );
                    // ------------------------------------------------------

                    // update floor_sizes
                    // ------------------------------------------------------
                    foreach ($building_data['building_floors'] as $floor_size) {
                        $floor_size['pj_lot_building_a_id'] = $building->id;
                        // update data
                        // --------------------------------------------------
                        if (empty($floor_size['deleted'])) {
                            $floor_sizes->updateOrCreate([
                                'id'                    => $floor_size['id'],
                                'pj_lot_building_a_id'  => $building->id
                            ], $floor_size );
                        }
                        // delete data
                        // --------------------------------------------------
                        else{
                            $delete_floor_size = PjBuildingFloorSize::findOrFail($floor_size['id']);
                            $delete_floor_size->delete();
                        }
                    }
                    // ------------------------------------------------------

                    // update pj_lot_building_owners
                    // ------------------------------------------------------
                    foreach ($building_data['building_owners'] as $building_owner) {
                        $building_owner['pj_lot_building_a_id'] = $building->id;
                        $building_owner['pj_property_owners_id'] = $owners_id[$building_owner['pj_property_owners_id']];
                        // update data
                        // --------------------------------------------------
                        if (empty($building_owner['deleted'])) {
                            $building_owners->updateOrCreate([
                                'id'                    => $building_owner['id'],
                                'pj_lot_building_a_id'  => $building->id
                            ], $building_owner );
                        }
                        // delete data
                        // --------------------------------------------------
                        else{
                            $delete_building_owner = PjLotBuildingOwner::findOrFail($building_owner['id']);
                            $delete_building_owner->delete();
                        }

                    }
                    // ------------------------------------------------------
                }
            }

            // ------------------- by Yoga Pratama ----------------------
            // added function for delete purchase target contractor when not have residential, road or building
            // --------------------------------------------------------------
            $owners         = $this->property_owners::where('pj_property_id', $property->id)->get();
            foreach($owners as $owner) {
                // check if owner have residential, road or building
                $count_redidential  = PjLotResidentialOwner::where('pj_property_owners_id', $owner->id)->count();
                $count_road         = PjLotRoadOwner::where('pj_property_owners_id', $owner->id)->count();
                $count_building     = PjLotBuildingOwner::where('pj_property_owners_id', $owner->id)->count();
                $total_count        = $count_redidential+$count_road+$count_building;
                // if no have, delete it
                $arr_commons        = [];
                if($total_count == 0) {
                    $contractors = PjLotContractor::where('pj_property_owner_id', $owner->id)->get();
                    foreach($contractors as $contractor) {
                        PjPurchaseTargetContractor::where('pj_lot_contractor_id', $contractor->id)->delete();
                        array_push($arr_commons, PjLotCommon::find($contractor->pj_lot_common_id));
                    }
                    PjLotContractor::where('pj_property_owner_id', $owner->id)->delete();
                    foreach($arr_commons as $arr_common) {
                        $arr_common->delete();
                    }
                } else {
                    $contractors = PjLotContractor::where('pj_property_owner_id', $owner->id)->get();
                    if($contractors->count() == 0) {
                        if($count_redidential > 0) {
                            $redidential_datas = PjLotResidentialOwner::where('pj_property_owners_id', $owner->id)->get();
                            foreach($redidential_datas as $redidential_data) {
                                $new_common = PjLotCommon::create([
                                    'pj_property_id'            => $property->id,
                                    'pj_lot_residential_a_id'   => $redidential_data->pj_lot_residential_a_id,
                                    'pj_lot_road_a_id'          => null,
                                    'pj_lot_building_a_id'      => null,
                                ]);
                                PjLotContractor::create([
                                    'name'                      => $owner->name,
                                    'same_to_owner'             => 1,
                                    'pj_lot_common_id'          => $new_common->id,
                                    'pj_property_owner_id'      => $owner->id,
                                ]);
                            }
                        }
                        if($count_road > 0) {
                            $road_datas = PjLotRoadOwner::where('pj_property_owners_id', $owner->id)->get();
                            foreach($road_datas as $road_data) {
                                $new_common = PjLotCommon::create([
                                    'pj_property_id'            => $property->id,
                                    'pj_lot_residential_a_id'   => null,
                                    'pj_lot_road_a_id'          => $road_data->pj_lot_road_a_id,
                                    'pj_lot_building_a_id'      => null,
                                ]);
                                PjLotContractor::create([
                                    'name'                      => $owner->name,
                                    'same_to_owner'             => 1,
                                    'pj_lot_common_id'          => $new_common->id,
                                    'pj_property_owner_id'      => $owner->id,
                                ]);
                            }
                        }
                        if($count_building > 0) {
                            $building_datas = PjLotBuildingOwner::where('pj_property_owners_id', $owner->id)->get();
                            foreach($building_datas as $building_data) {
                                $new_common = PjLotCommon::create([
                                    'pj_property_id'            => $property->id,
                                    'pj_lot_residential_a_id'   => null,
                                    'pj_lot_road_a_id'          => null,
                                    'pj_lot_building_a_id'      => $building_data->pj_lot_building_a_id,
                                ]);
                                PjLotContractor::create([
                                    'name'                      => $owner->name,
                                    'same_to_owner'             => 1,
                                    'pj_lot_common_id'          => $new_common->id,
                                    'pj_property_owner_id'      => $owner->id,
                                ]);
                            }
                        }
                    }
                }
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Response Data
            // --------------------------------------------------------------
            $response_data = new \stdClass;
            $response_data->property = $property;
            $response_data->property_owners = $this->property_owners::where('pj_property_id', $property->id)->get();
            $response_data->residentials = $this->residentials_a::where('pj_property_id', $property->id)->with('use_districts', 'build_ratios', 'floor_ratios', 'residential_owners')->get();
            $response_data->roads = $this->roads_a::where('pj_property_id', $property->id)->with('use_districts', 'build_ratios', 'floor_ratios', 'road_owners')->get();
            $response_data->buildings = $this->buildings_a::where('pj_property_id', $property->id)->with('building_floors', 'building_owners')->get();
            $response_data->stat_check = $stat_check;

            // --------------------------------------------------------------
            // return success message with response data
            // --------------------------------------------------------------
            return response()->json([
                'status'  => 'success',
                'message' => __('label.success_update_message'),
                'data'    => $response_data
            ]);
            // --------------------------------------------------------------
        }
        catch (\Exception $error) {
            // log error and return error message
            // --------------------------------------------------------------
            Log::error([
                'message'   => $error->getMessage(),
                'file'      => $error->getFile().':'.$error->getLine(),
                'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
            ]);
            // --------------------------------------------------------------
            return response()->json([
                'status'  => 'error',
                'message' => __('label.failed_update_message'),
                'error'   => $error->getMessage()
            ], 500);
        }
    }

    // ----------------------------------------------------------------------
    // Project Assist A Delete Handler
    // ----------------------------------------------------------------------
    public function assist_a_delete($project_id, $type, Request $request){
        try {
            switch ($type) {
                // ----------------------------------------------------------
                case 'property_owners':
                    $property_owners = $this->property_owners::find($request->id);
                    $property_owners->delete();
                    break;
                // ----------------------------------------------------------
                case 'residential':
                    $residential = $this->residentials_a::find($request->id);
                    $residential->delete();
                    // ------------------------------------------------------
                    PjLotResidentialParcelUseDistrict::whereNull('pj_lot_residential_a_id')->delete();
                    PjLotResidentialParcelBuildRatio::whereNull('pj_lot_residential_a_id')->delete();
                    PjLotResidentialParcelFloorRatio::whereNull('pj_lot_residential_a_id')->delete();
                    PjLotResidentialOwner::whereNull('pj_lot_residential_a_id')->delete();
                    break;
                // ----------------------------------------------------------
                case 'road':
                    $road = $this->roads_a::find($request->id);
                    $road->delete();
                    // ------------------------------------------------------
                    PjLotRoadParcelUseDistrict::whereNull('pj_lot_road_a_id')->delete();
                    PjLotRoadParcelBuildRatio::whereNull('pj_lot_road_a_id')->delete();
                    PjLotRoadParcelFloorRatio::whereNull('pj_lot_road_a_id')->delete();
                    PjLotRoadOwner::whereNull('pj_lot_road_a_id')->delete();
                    break;
                // ----------------------------------------------------------
                case 'building':
                    $building = $this->buildings_a::find($request->id);
                    $building->delete();
                    // ------------------------------------------------------
                    PjBuildingFloorSize::whereNull('pj_lot_building_a_id')->delete();
                    PjLotBuildingOwner::whereNull('pj_lot_building_a_id')->delete();
                    break;
                // ----------------------------------------------------------
                case 'section':
                    $section = $request->section;
                    if (isset($request->property_id)) {
                        switch ($section) {
                            case 'exists_land_residential':
                                // Delete residentials on section
                                // ------------------------------------------
                                $this->residentials_a::where('pj_property_id', $request->property_id)->delete();
                                // Delete related residential data
                                // ------------------------------------------
                                PjLotResidentialParcelUseDistrict::whereNull('pj_lot_residential_a_id')->delete();
                                PjLotResidentialParcelBuildRatio::whereNull('pj_lot_residential_a_id')->delete();
                                PjLotResidentialParcelFloorRatio::whereNull('pj_lot_residential_a_id')->delete();
                                PjLotResidentialOwner::whereNull('pj_lot_residential_a_id')->delete();
                                break;
                                // ------------------------------------------
                            case 'exists_road_residential':
                                // Delete roads on section
                                // ------------------------------------------
                                $this->roads_a::where('pj_property_id', $request->property_id)->delete();
                                // Delete related residential data
                                // ------------------------------------------
                                PjLotRoadParcelUseDistrict::whereNull('pj_lot_road_a_id')->delete();
                                PjLotRoadParcelBuildRatio::whereNull('pj_lot_road_a_id')->delete();
                                PjLotRoadParcelFloorRatio::whereNull('pj_lot_road_a_id')->delete();
                                PjLotRoadOwner::whereNull('pj_lot_road_a_id')->delete();
                                break;
                                // ------------------------------------------
                            case 'exists_building_residential':
                                // Delete roads on section
                                // ------------------------------------------
                                $this->buildings_a::where('pj_property_id', $request->property_id)->delete();
                                // Delete related residential data
                                // ------------------------------------------
                                PjBuildingFloorSize::whereNull('pj_lot_building_a_id')->delete();
                                PjLotBuildingOwner::whereNull('pj_lot_building_a_id')->delete();
                                break;
                                // ------------------------------------------
                            default: break;
                        }
                    }
                    break;
                // ----------------------------------------------------------
                default: break;
            }
            // ------------------- by Yoga Pratama ----------------------
            // added function for delete purchase target contractor when not have residential, road or building
            // --------------------------------------------------------------
            $owners         = $this->property_owners::where('pj_property_id', $request->property_id)->get();
            foreach($owners as $owner) {
                // check if owner have residential, road or building
                $count_redidential  = PjLotResidentialOwner::where('pj_property_owners_id', $owner->id)->count();
                $count_road         = PjLotRoadOwner::where('pj_property_owners_id', $owner->id)->count();
                $count_building     = PjLotBuildingOwner::where('pj_property_owners_id', $owner->id)->count();
                $total_count        = $count_redidential+$count_road+$count_building;
                // if no have, delete it
                $arr_commons        = [];
                if($total_count == 0) {
                    $contractors = PjLotContractor::where('pj_property_owner_id', $owner->id)->get();
                    foreach($contractors as $contractor) {
                        PjPurchaseTargetContractor::where('pj_lot_contractor_id', $contractor->id)->delete();
                        array_push($arr_commons, PjLotCommon::find($contractor->pj_lot_common_id));
                    }
                    PjLotContractor::where('pj_property_owner_id', $owner->id)->delete();
                    foreach($arr_commons as $arr_common) {
                        $arr_common->delete();
                    }
                }
            }
            // --------------------------------------------------------------

            // return succes message
            // --------------------------------------------------------------
            return response()->json([
                'status'  => 'success',
                'message' => __('label.success_delete_message'),
            ]);
            // --------------------------------------------------------------
        }
        catch (\Exception $error) {
            // log error and return error message
            // --------------------------------------------------------------
            Log::error([
                'message'   => $error->getMessage(),
                'file'      => $error->getFile().':'.$error->getLine(),
                'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
            ]);
            // --------------------------------------------------------------
            return response()->json([
                'status'  => 'error',
                'message' => __('label.failed_delete_message'),
                'error'   => $error->getMessage()
            ], 500);
        }
    }


    // ----------------------------------------------------------------------
    // Project Assist B Page
    // ----------------------------------------------------------------------
    public function assistB($id){
        // get related data from database
        // ------------------------------------------------------------------
        $user_role = auth()->user()->user_role->name;
        $project = $this->project::findOrFail($id);
        $property = $this->property::where('project_id', $id)->with('owners','restrictions')->firstOrFail();
        // ------------------------------------------------------------------
        $residentials = $this->residentials_a::where('pj_property_id', $property->id ?? null)->with(
            'use_districts',
            'build_ratios',
            'floor_ratios',
            'residential_owners',
            'residential_b')->get();
        $roads = $this->roads_a::where('pj_property_id', $property->id ?? null)->with(
            'use_districts',
            'build_ratios',
            'floor_ratios',
            'road_owners',
            'road_b')->get();
        $buildings = $this->buildings_a::where('pj_property_id', $property->id ?? null)->with(
            'building_floors',
            'building_owners')->get();
        $stat_check = $this->stat_check::where('project_id', $project->id)->where('screen', 'pj_assist_b')->first();
        // ------------------------------------------------------------------

        // assign data
        // ------------------------------------------------------------------
        $data = new \stdClass;
        $data->editable = $user_role == 'accounting_firm' ? false : true;
        $data->master_values = MasterValue::select('id','type','value')->where('masterdeleted', 0)->get();
        $data->master_regions = MasterRegion::select('id','name','type')->where('type', 'city')->where('is_enable', 1)->get();
        // ------------------------------------------------------------------
        $data->project = $project;
        $data->property = $property;
        $data->residentials = $residentials;
        $data->roads = $roads;
        $data->buildings = $buildings;
        $data->stat_check = $stat_check;
        // ------------------------------------------------------------------
        $data->page_title = "アシストB::{$project->title}";
        $data->update_url = route('project.assist.b.update', $id);
        // ------------------------------------------------------------------
        return view( 'backend.project.assist-b.assist-b', (array) $data );
    }


    // ----------------------------------------------------------------------
    // Project Assist B Update Handler
    // ----------------------------------------------------------------------
    public function assist_b_update($id, Request $request){
        try {
            // --------------------------------------------------------------
            $project_id = $id;
            $property = $this->property::find($request->property['id']);
            $property_restrictions = new PjPropertyRestriction();
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // STATUS CHECK
            // --------------------------------------------------------------
            // create or update stat_check
            $data_status = $request->stat_check;
            $data_status['project_id'] = $project_id;
            $stat_check = $this->stat_check->updateOrCreate([
                'project_id' => $project_id,
                'screen'     => 'pj_assist_b'
            ], $data_status );
            // --------------------------------------------------------------
            // check status -> if db not changes and status is 1 prevent saving data
            $status_changed = $stat_check->getChanges();
            $status_created = $stat_check->wasRecentlyCreated;
            if (!isset($status_changed['status']) && $stat_check->status == 1 && !$status_created) {
                return response()->json([
                    'status'  => 'warning',
                    'message' => '「完」の場合編集できません。'
                ]);
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // LAND SECTION
            // --------------------------------------------------------------
            // Property
            $data_property = $request->property;
            $property = $this->property->findOrFail($data_property['id']);
            $property->update($data_property);
            // --------------------------------------------------------------
            // Property Restrictions create or update
            foreach ($request->property_restrictions as $restriction) {
                // update data
                // --------------------------------------------------
                if (empty($restriction['deleted'])) {
                    $restriction['pj_property_id'] = $property->id;
                    $property_restrictions->updateOrCreate([
                        'id'             => $restriction['id'],
                        'pj_property_id' => $property->id
                    ], $restriction );
                }
                // delete data
                // --------------------------------------------------
                else{
                    $delete_restriction = PjPropertyRestriction::find($restriction['id']);
                    $delete_restriction->delete();
                }
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // RESIDENTIAL SECTION
            // --------------------------------------------------------------
            foreach ($request->residentials as $residential) {
                $residential_update = $this->residentials_b->find($residential['id']);
                $residential_update->update($residential);
            }

            // --------------------------------------------------------------
            // ROAD SECTION
            // --------------------------------------------------------------
            foreach ($request->roads as $road) {
                $road_update = $this->roads_b->find($road['id']);
                $road_update->update($road);
            }


            // Response Data
            // --------------------------------------------------------------
            $response_data = new \stdClass;
            $response_data->property = $this->property::where('project_id', $project_id)->with('owners','restrictions')->first();
            $response_data->stat_check = $stat_check;

            // return success message with response data
            // --------------------------------------------------------------
            return response()->json([
                'status'  => 'success',
                'message' => __('label.success_update_message'),
                'data'    => $response_data
            ]);
            // --------------------------------------------------------------

        }
        catch (\Exception $error) {
            // log error and return error message
            // --------------------------------------------------------------
            Log::error([
                'message'   => $error->getMessage(),
                'file'      => $error->getFile().':'.$error->getLine(),
                'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
            ]);
            // --------------------------------------------------------------
            return response()->json([
                'status'  => 'error',
                'message' => __('label.failed_update_message'),
                'error'   => $error->getMessage()
            ], 500);
        }
    }

}
// --------------------------------------------------------------------------
