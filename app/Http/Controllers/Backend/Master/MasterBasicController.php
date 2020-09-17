<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
// --------------------------------------------------------------------------
use App\Models\MasterValue;
use App\Models\MasterRegion;
use App\Models\Project;
use App\Models\PjProperty;
use App\Models\PjPropertyOwner;
// --------------------------------------------------------------------------
use App\Models\PjLotResidentialA;
use App\Models\PjLotResidentialPurchase;
use App\Models\PjLotResidentialParcelUseDistrict;
use App\Models\PjLotResidentialParcelBuildRatio;
use App\Models\PjLotResidentialParcelFloorRatio;
use App\Models\PjLotResidentialOwner;
// --------------------------------------------------------------------------
use App\Models\PjLotRoadA;
use App\Models\PjLotRoadPurchase;
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
use App\Models\MasBasic;
use App\Models\MasBasicRestriction;
// -----------------------------------------------------------------------------
use App\Models\MasLotResidential;
use App\Models\MasLotResidentialParcelUseDistrict;
use App\Models\MasLotResidentialParcelFloorRatio;
use App\Models\MasLotResidentialParcelBuildRatio;
// -----------------------------------------------------------------------------
use App\Models\MasLotRoad;
use App\Models\MasLotRoadParcelUseDistrict;
use App\Models\MasLotRoadParcelFloorRatio;
use App\Models\MasLotRoadParcelBuildRatio;
// -----------------------------------------------------------------------------
use App\Models\MasLotBuilding;
use App\Models\MasLotBuildingFloorSize;

class MasterBasicController extends Controller
{
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

        $this->mas_basic = new MasBasic();
        $this->mas_residentials = new MasLotResidential();
        $this->mas_roads = new MasLotRoad();
        $this->mas_buildings = new MasLotBuilding();
    }

    public function index($id)
    {
        $data = $this->source_data($id);
        return view( 'backend.master.basic.form', (array) $data );
    }

    public function update(Request $request)
    {
        try {
            DB::transaction(function () use($request) {
                // ---------------------------------------------------------------------
                // save or update mas basic data with its relationship
                // ---------------------------------------------------------------------

                // remove unnecesary data
                // ---------------------------------------------------------------------
                $mas_basic_input = collect($request->mas_basic)->except([
                    'registry_size', 'registry_size_status', 'survey_size',
                    'survey_size_status', 'fixed_asset_tax_route_value', 'restriction_extra',
                    'company_id_organizer', 'organizer_realestate_explainer', 'project_address',
                    'project_address_extra', 'offer_route', 'offer_date', 'project_type',
                    'project_size', 'project_size_status', 'purchase_price', 'project_status',
                ]);
                // ---------------------------------------------------------------------

                // save or update mas basic data
                // ---------------------------------------------------------------------
                $mas_basic = $this->mas_basic::updateOrCreate(
                    ['id' => $mas_basic_input['id']],
                    $mas_basic_input->toArray()
                );
                // ---------------------------------------------------------------------

                // save or update mas basic restriction data
                // ---------------------------------------------------------------------
                $restrictions = $request->basic_restrictions;
                foreach ($restrictions as $key => $restriction) {
                    $restriction_input = collect($restriction)->except([
                        'pj_property_id', 'deleted'
                    ]);

                    if (empty($restriction['deleted'])) {
                        $restriction_input['mas_basic_id'] = $mas_basic->id;
                        $restriction = MasBasicRestriction::updateOrCreate(
                            ['id' => $restriction_input['id']],
                            $restriction_input->toArray()
                        );
                    }else {
                        MasBasicRestriction::findOrFail($restriction_input['id'])->delete();
                    }
                }
                // ---------------------------------------------------------------------

                // ---------------------------------------------------------------------
                // save or update mas lot residential with its relation
                // ---------------------------------------------------------------------
                $residentials = $request['residentials'];
                foreach ($residentials as $key => $residential) {

                    // remove unnecesary data
                    // -----------------------------------------------------------------
                    $residential_input = collect($residential)->except([
                        'build_ratios', 'floor_ratios', 'use_districts',
                        'parcel_size', 'parcel_size_survey', 'pj_property_id',
                        'pj_lot_residential_b_id', 'scenic_area'
                    ]);
                    // -----------------------------------------------------------------

                    // save or update mas lot residential
                    // -----------------------------------------------------------------
                    $mas_residential = $this->mas_residentials::updateOrCreate(
                        ['id' => $residential_input['id']],
                        $residential_input->toArray()
                    );
                    // -----------------------------------------------------------------

                    // use districts relationship
                    // -----------------------------------------------------------------
                    $use_districts = $residential['use_districts'];
                    foreach ($use_districts as $key => $use_district) {

                        // remove unnecesary data
                        $use_district_input = collect($use_district)->except([
                            'pj_lot_residential_a_id', 'deleted'
                        ]);

                        // save or update mas lot residential parcel use dictrict
                        // -------------------------------------------------------------
                        if (empty($use_district['deleted'])) {
                            $use_district_input['mas_lot_residential_id'] = $mas_residential->id;
                            $use_district = MasLotResidentialParcelUseDistrict::updateOrCreate(
                                ['id' => $use_district_input['id']],
                                $use_district_input->toArray()
                            );
                        }else {
                            MasLotResidentialParcelUseDistrict::findOrFail($use_district_input['id'])->delete();
                        }
                        // -------------------------------------------------------------
                    }
                    // -----------------------------------------------------------------

                    // floor ratios relationship
                    // -----------------------------------------------------------------
                    $floor_ratios = $residential['floor_ratios'];
                    foreach ($floor_ratios as $key => $floor_ratio) {

                        // remove unnecesary data
                        $floor_ratio_input = collect($floor_ratio)->except([
                            'pj_lot_residential_a_id', 'deleted'
                        ]);

                        // save or update mas lot residential parcel floor ratio
                        // -------------------------------------------------------------
                        if (empty($floor_ratio['deleted'])) {
                            $floor_ratio_input['mas_lot_residential_id'] = $mas_residential->id;
                            $floor_ratio = MasLotResidentialParcelFloorRatio::updateOrCreate(
                                ['id' => $floor_ratio_input['id']],
                                $floor_ratio_input->toArray()
                            );
                        }else {
                            MasLotResidentialParcelFloorRatio::findOrFail($floor_ratio_input['id'])->delete();
                        }
                        // -------------------------------------------------------------
                    }
                    // -----------------------------------------------------------------

                    // build ratios relationship
                    // -----------------------------------------------------------------
                    $build_ratios = $residential['build_ratios'];
                    foreach ($build_ratios as $key => $build_ratio) {

                        // remove unnecesary data
                        $build_ratio_input = collect($build_ratio)->except([
                            'pj_lot_residential_a_id', 'deleted'
                        ]);

                        // save or update mas lot residential parcel build ratio
                        // -------------------------------------------------------------
                        if (empty($build_ratio['deleted'])) {
                            $build_ratio_input['mas_lot_residential_id'] = $mas_residential->id;
                            $build_ratio = MasLotResidentialParcelBuildRatio::updateOrCreate(
                                ['id' => $build_ratio_input['id']],
                                $build_ratio_input->toArray()
                            );
                        }else {
                            MasLotResidentialParcelBuildRatio::findOrFail($build_ratio_input['id'])->delete();
                        }
                        // -------------------------------------------------------------
                    }
                    // -----------------------------------------------------------------
                }
                // ---------------------------------------------------------------------

                // ---------------------------------------------------------------------
                // save or update mas lot road with its relation
                // ---------------------------------------------------------------------
                $roads = $request['roads'];
                foreach ($roads as $key => $road) {

                    // remove unnecesary data
                    // -----------------------------------------------------------------
                    $road_input = collect($road)->except([
                        'build_ratios', 'floor_ratios', 'use_districts',
                        'parcel_size', 'parcel_size_survey', 'pj_property_id',
                        'pj_lot_road_b_id', 'scenic_area'
                    ]);
                    // -----------------------------------------------------------------

                    // save or update mas lot road
                    // -----------------------------------------------------------------
                    $mas_road = $this->mas_roads::updateOrCreate(
                        ['id' => $road_input['id']],
                        $road_input->toArray()
                    );
                    // -----------------------------------------------------------------

                    // use districts relationship
                    // -----------------------------------------------------------------
                    $use_districts = $road['use_districts'];
                    foreach ($use_districts as $key => $use_district) {

                        // remove unnecesary data
                        $use_district_input = collect($use_district)->except([
                            'pj_lot_road_a_id', 'deleted'
                        ]);

                        // save or update mas lot road parcel use dictrict
                        // -------------------------------------------------------------
                        if (empty($use_district['deleted'])) {
                            $use_district_input['mas_lot_road_id'] = $mas_road->id;
                            $use_district = MasLotRoadParcelUseDistrict::updateOrCreate(
                                ['id' => $use_district_input['id']],
                                $use_district_input->toArray()
                            );
                        }else {
                            MasLotRoadParcelUseDistrict::findOwner($use_district_input['id'])->delete();
                        }
                        // -------------------------------------------------------------
                    }
                    // -----------------------------------------------------------------

                    // floor ratios relationship
                    // -----------------------------------------------------------------
                    $floor_ratios = $road['floor_ratios'];
                    foreach ($floor_ratios as $key => $floor_ratio) {

                        // remove unnecesary data
                        $floor_ratio_input = collect($floor_ratio)->except([
                            'pj_lot_road_a_id', 'deleted'
                        ]);

                        // save or update mas lot road parcel floor ratio
                        // -------------------------------------------------------------
                        if (empty($floor_ratio['deleted'])) {
                            $floor_ratio_input['mas_lot_road_id'] = $mas_road->id;
                            $floor_ratio = MasLotRoadParcelFloorRatio::updateOrCreate(
                                ['id' => $floor_ratio_input['id']],
                                $floor_ratio_input->toArray()
                            );
                        }else {
                            MasLotRoadParcelFloorRatio::findOrFail($floor_ratio_input['id'])->delete();
                        }
                        // -------------------------------------------------------------
                    }
                    // -----------------------------------------------------------------

                    // build ratios relationship
                    // -----------------------------------------------------------------
                    $build_ratios = $road['build_ratios'];
                    foreach ($build_ratios as $key => $build_ratio) {

                        // remove unnecesary data
                        $build_ratio_input = collect($build_ratio)->except([
                            'pj_lot_road_a_id', 'deleted'
                        ]);

                        // save or update mas lot road parcel build ratio
                        // -------------------------------------------------------------
                        if (empty($build_ratio['deleted'])) {
                            $build_ratio_input['mas_lot_road_id'] = $mas_road->id;
                            $build_ratio = MasLotRoadParcelBuildRatio::updateOrCreate(
                                ['id' => $build_ratio_input['id']],
                                $build_ratio_input->toArray()
                            );
                        }else {
                            MasLotRoadParcelBuildRatio::findOrFail($build_ratio_input['id'])->delete();
                        }
                        // -------------------------------------------------------------
                    }
                    // -----------------------------------------------------------------
                }
                // ---------------------------------------------------------------------

                // ---------------------------------------------------------------------
                // save or update mas lot building with its relation
                // ---------------------------------------------------------------------
                $buildings = $request['buildings'];
                foreach ($buildings as $key => $building) {

                    // remove unnecesary data
                    // -----------------------------------------------------------------
                    $building_input = collect($building)->except([
                        'building_floors', 'pj_property_id', 'exists_building_residential',
                        'city', 'building_date_western'
                    ]);
                    // -----------------------------------------------------------------

                    // save or update mas lot building
                    // -----------------------------------------------------------------
                    $building_input['building_date_year'] = $building['building_date_western'] ?? null;
                    $mas_building = $this->mas_buildings::updateOrCreate(
                        ['id' => $building_input['id']],
                        $building_input->toArray()
                    );
                    // -----------------------------------------------------------------

                    // build ratios relationship
                    // -----------------------------------------------------------------
                    $building_floors = $building['building_floors'];
                    foreach ($building_floors as $key => $building_floor) {

                        // remove unnecesary data
                        $building_floor_input = collect($building_floor)->except(['pj_lot_building_a_id']);

                        // save or update mas lot building floor size
                        // -------------------------------------------------------------
                        $building_floor_input['mas_lot_building_id'] = $mas_building->id;
                        $building_floor = MasLotBuildingFloorSize::updateOrCreate(
                            ['id' => $building_floor_input['id']],
                            $building_floor_input->toArray()
                        );
                        // -------------------------------------------------------------
                    }
                    // -----------------------------------------------------------------
                }
                // ---------------------------------------------------------------------
            });

            // ------------------------------------------------------------------
            // prepare response data
            // ------------------------------------------------------------------
            $id = $request->project['id'];
            $response_data = $this->source_data($id);

            return response()->json([
                'status' => 'success',
                'message' => __('label.success_update_message'),
                'data' => $response_data
            ]);
            // ---------------------------------------------------------------------
        } catch (\Exception $error) {
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
    // Delete Residential, Road and Building Section
    // ----------------------------------------------------------------------
    public function delete($project_id, Request $request){
        try {
            $section_type = null; // deleted section

            switch ($request->field) {
                case 'exists_land_residential':
                    foreach ($request->mas_lot as $key => $residential) {
                        // Delete residentials on section
                        // ------------------------------------------
                        MasLotResidential::where('id', $residential['id'])->delete();
                        // Delete related residential data
                        // ------------------------------------------
                        MasLotResidentialParcelUseDistrict::whereNull('mas_lot_residential_id')->delete();
                        MasLotResidentialParcelBuildRatio::whereNull('mas_lot_residential_id')->delete();
                        MasLotResidentialParcelFloorRatio::whereNull('mas_lot_residential_id')->delete();
                    }
                    $section_type = "residential";
                    break;
                    // ------------------------------------------
                case 'exists_road_residential':
                    foreach ($request->mas_lot as $key => $road) {
                        // Delete roads on section
                        // ------------------------------------------
                        MasLotRoad::where('id', $road['id'])->delete();
                        // Delete related road data
                        // ------------------------------------------
                        MasLotRoadParcelUseDistrict::whereNull('mas_lot_road_id')->delete();
                        MasLotRoadParcelBuildRatio::whereNull('mas_lot_road_id')->delete();
                        MasLotRoadParcelFloorRatio::whereNull('mas_lot_road_id')->delete();
                    }
                    $section_type = "road";
                    break;
                    // ------------------------------------------
                case 'exists_building_residential':
                    foreach ($request->mas_lot as $key => $building) {
                        // Delete mas lot building
                        // ------------------------------------------
                        MasLotBuilding::where('id', $building['id'])->delete();
                        // Delete related residential data
                        // ------------------------------------------
                        MasLotBuildingFloorSize::whereNull('mas_lot_building_id')->delete();
                    }
                    $section_type = "building";
                    break;
                    // ------------------------------------------
                default: break;
                // ----------------------------------------------------------
            }

            // ------------------------------------------------------------------
            // prepare response data
            // ------------------------------------------------------------------
            $response_data = $this->source_data($project_id);
            $response_data->section_type = $section_type;

            // return succes message
            // --------------------------------------------------------------
            return response()->json([
                'status'  => 'success',
                'data'    => $response_data,
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

    public function source_data($id)
    {
        // get related data from database
        // ------------------------------------------------------------------
        $user_role = auth()->user()->user_role->name;
        $project = $this->project::findOrFail($id);
        $property = $this->property::where('project_id', $id)->with('owners','restrictions')->firstOrFail();
        $mas_basic = $this->mas_basic::where('project_id', $id)->with('restrictions')->first();
        $purchase_sale = $project->purchaseSale;
        // ------------------------------------------------------------------
        $residentials = $this->residentials_a::where('pj_property_id', $property->id ?? null)->with(
            'use_districts', 'build_ratios',
            'floor_ratios', 'residential_owners',
            'residential_b', 'residential_purchase',
            'mas_residential')->get();
        $roads = $this->roads_a::where('pj_property_id', $property->id ?? null)->with(
            'use_districts', 'build_ratios',
            'floor_ratios', 'road_owners',
            'road_b', 'road_purchase',
            'mas_road')->get();
        $buildings = $this->buildings_a::where('pj_property_id', $property->id ?? null)->with(
            'building_floors', 'building_owners',
            'mas_building')->get();
        // ------------------------------------------------------------------

        // ---------------------------------------------------------------------
        // assign data from property and purchase sale if mas basic = null
        // ---------------------------------------------------------------------
        if (!$mas_basic) {
            $mas_basic = factory(MasBasic::class)->states('init')->make();
            $mas_basic = $mas_basic->fill(collect($property)->except(['owners', 'restrictions'])->toArray());
            $mas_basic = $mas_basic->fill($purchase_sale->toArray());

            // assign data from property restriction to mas lot restriction
            $mas_basic->restrictions[0] = factory(MasBasicRestriction::class)->states('init')->make();
            foreach ($property->restrictions as $key => $restriction) {
                $mas_basic->restrictions[$key] = factory(MasBasicRestriction::class)->states('init')->make();
                $mas_basic->restrictions[$key] = $mas_basic->restrictions[$key]->fill(collect($restriction)->toArray());
            }

            // set basic parcel build ratio
            // get data from residential and road build ratio
            // -----------------------------------------------------------------
            $parcel_build_ratio = collect([]);
            $parcel_build_ratio->push($residentials->map(function ($residential, $key) {
                return $residential->build_ratios;
            }));
            $parcel_build_ratio->push($roads->map(function ($road, $key) {
                return $road->build_ratios;
            }));
            $parcel_build_ratio = $parcel_build_ratio->flatten(2);
            $mas_basic->basic_parcel_build_ratio = $parcel_build_ratio[0]->value ?? null;
            // -----------------------------------------------------------------

            // set basic parcel floor ratio
            // get data from residential and road floor ratio
            // -----------------------------------------------------------------
            $parcel_floor_ratio = collect([]);
            $parcel_floor_ratio->push($residentials->map(function ($residential, $key) {
                return $residential->floor_ratios;
            }));
            $parcel_floor_ratio->push($roads->map(function ($road, $key) {
                return $road->floor_ratios;
            }));
            $parcel_floor_ratio = $parcel_floor_ratio->flatten(2);
            $mas_basic->basic_parcel_floor_ratio = $parcel_floor_ratio[0]->value ?? null;
            // -----------------------------------------------------------------
        }
        // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------
        // assign data from assist_a, assist_b and purchase_create
        // if mas_lot_residential and mas_lot_road = null
        // ---------------------------------------------------------------------
        $lot_kinds = array('residential', 'road');
        foreach ($lot_kinds as $key => $lot_kind) {

            $lot_as = $lot_kind == 'residential' ? $residentials : $roads; // find out residentials or roads

            // assist a to assist b, purchase and mas_lot relation
            // -----------------------------------------------------------------
            $lot_b = $lot_kind == 'residential' ? 'residential_b' : 'road_b';
            $lot_purchase = $lot_kind == 'residential' ? 'residential_purchase' : 'road_purchase';
            $mas_lot = $lot_kind == 'residential' ? 'mas_residential' : 'mas_road';

            $mas_lot_kind = $lot_kind == 'residential' ? 'mas_lot_residential' : 'mas_lot_road'; // new variable
            $mas_model = $lot_kind == 'residential' ? MasLotResidential::class : MasLotRoad::class;
            $lot_purchase_model = $lot_kind == 'residential' ? PjLotResidentialPurchase::class : PjLotRoadPurchase::class;
            // -----------------------------------------------------------------

            foreach ($lot_as as $key => $lot_a) {
                if (!$lot_a->$mas_lot && !$mas_basic->id) { // relation between assist a and mas lot
                    // create residential and road variable without unnecesary relation
                    // ---------------------------------------------------------
                    $lot_kind_only = collect($lot_a)->except([
                        "land_category",
                        // "use_districts",
                        // "build_ratios", "floor_ratios",
                        "{$lot_kind}_owners", "{$lot_kind}_b",
                        "{$lot_kind}_purchase", "mas_{$lot_kind}",
                    ]);
                    // ---------------------------------------------------------

                    // set id to null on use district, build ratio and floor ratio relationship
                    // ---------------------------------------------------------
                    $use_districts = collect($lot_kind_only['use_districts'])->map(function ($item, $key) {
                        $item['id'] = null;
                        return $item;
                    });
                    $lot_kind_only['use_districts'] = $use_districts->toArray();
                    // ---------------------------------------------------------
                    $build_ratios = collect($lot_kind_only['build_ratios'])->map(function ($item, $key) {
                        $item['id'] = null;
                        return $item;
                    });
                    $lot_kind_only['build_ratios'] = $build_ratios->toArray();
                    // ---------------------------------------------------------
                    $floor_ratios = collect($lot_kind_only['floor_ratios'])->map(function ($item, $key) {
                        $item['id'] = null;
                        return $item;
                    });
                    $lot_kind_only['floor_ratios'] = $floor_ratios->toArray();
                    // ---------------------------------------------------------

                    // assign default data to mas lot residential or mas lot road
                    $lot_a->$mas_lot_kind = factory($mas_model)->states('init')->make();

                    // pj residential purchase or pj lot purchase is null
                    // assign default data from factory
                    // ---------------------------------------------------------
                    if ($lot_a->{$lot_purchase} == null) {
                        $lot_a->{$lot_purchase} = factory($lot_purchase_model)->states('init')->make();
                        if ($lot_kind == 'residential') $lot_a->{$lot_purchase}->pj_lot_residential_a_id = $lot_a->id;
                        if ($lot_kind == 'road') $lot_a->{$lot_purchase}->pj_lot_road_a_id = $lot_a->id;
                    }
                    // ---------------------------------------------------------

                    // assign assist a, assist b and purchase to mas lot (new variable created before)
                    $lot_a->$mas_lot_kind = $lot_a->$mas_lot_kind->fill($lot_kind_only->toArray());
                    $lot_a->$mas_lot_kind = $lot_a->$mas_lot_kind->fill($lot_a->{$lot_b}->toArray());
                    $lot_a->$mas_lot_kind = $lot_a->$mas_lot_kind->fill($lot_a->{$lot_purchase}->toArray());
                }else {
                    if ($lot_a->$mas_lot) $lot_a->$mas_lot->load("use_districts", "build_ratios", "floor_ratios");
                    else {
                        if ($lot_kind == 'residential') $residentials = [];
                        elseif ($lot_kind == 'road') $roads = [];
                    }
                }
            }
        }
        // ---------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // assign data from assist_a, assist_b and purchase_create
        // id mas_lot_building = null
        // ----------------------------------------------------------------------
        foreach ($buildings as $key => $building) {
            if (!$building->mas_building && !$mas_basic->id) { // relation between assist a and mas lot

                // get residential or road data only (without relation)
                $building_only = collect($building)->except([
                    'building_floors',
                    'building_owners',
                    'mas_building',
                ]);

                // assign assist a, assist b and purchase to mas lot (new variable created before)
                $building->mas_lot_building = factory(MasLotBuilding::class)->states('init')->make();
                $building->mas_lot_building = $building->mas_lot_building->fill($building_only->toArray());
                $building->mas_lot_building->pj_lot_building_a_id = $building->id;

                // assign building floor relation
                foreach ($building->building_floors as $key => $building_floor) {
                    $building->mas_lot_building->building_floors[$key] = factory(MasLotBuildingFloorSize::class)->state('init')->make();
                    $building->mas_lot_building->building_floors[$key] = $building->mas_lot_building->building_floors[$key]->fill($building_floor->toArray());
                }
            }else {
                if ($building->mas_building) $building->mas_building->load("building_floors");
                else $buildings = [];
            }
        }
        // ---------------------------------------------------------------------

        // assign data
        // ------------------------------------------------------------------
        $data = new \stdClass;
        $data->editable = $user_role == 'accounting_firm' ? false : true;
        $data->master_values = MasterValue::select('id','type','value')->where('masterdeleted', 0)->get();
        $data->master_regions = MasterRegion::select('id','name','type')->where('type', 'city')->where('is_enable', 1)->get();
        // ------------------------------------------------------------------
        $data->project = $project;
        $data->mas_basic = $mas_basic;
        $data->residentials = $residentials;
        $data->roads = $roads;
        $data->buildings = $buildings;
        // ------------------------------------------------------------------
        $data->page_title = "基本データ::{$project->title}";
        $data->update_url = route('master.basic.update', $id);
        $data->delete_section = route('master.basic.delete', $id);
        // ------------------------------------------------------------------
        $data = $this->date_format($data); // format date
        // ------------------------------------------------------------------
        return $data;
    }

    public function date_format($data)
    {
        $format = 'Y/m/d';

        // change mas basic date format
        // ---------------------------------------------------------------------
        $data->mas_basic->project_urbanization_area_date = $data->mas_basic->project_urbanization_area_date ?
            Carbon::parse($data->mas_basic->project_urbanization_area_date)->format($format) : null;
        // ---------------------------------------------------------------------

        // change mas residential date format
        // ---------------------------------------------------------------------
        foreach ($data->residentials as $key => $residential) {
            if (!$residential->mas_residential) {
                $residential->mas_lot_residential->urbanization_area_date = $residential->mas_lot_residential->urbanization_area_date ?
                    Carbon::parse($residential->mas_lot_residential->urbanization_area_date)->format($format) : null;
            }else {
                $residential->mas_residential->urbanization_area_date = $residential->mas_residential->urbanization_area_date ?
                    Carbon::parse($residential->mas_residential->urbanization_area_date)->format($format) : null;
            }
        }
        // ---------------------------------------------------------------------

        // change mas road date format
        // ---------------------------------------------------------------------
        foreach ($data->roads as $key => $road) {
            if (!$road->mas_road) {
                $road->mas_lot_road->urbanization_area_date = $road->mas_lot_road->urbanization_area_date ?
                    Carbon::parse($road->mas_lot_road->urbanization_area_date)->format($format) : null;
            }else {
                $road->mas_road->urbanization_area_date = $road->mas_road->urbanization_area_date ?
                    Carbon::parse($road->mas_road->urbanization_area_date)->format($format) : null;
            }
        }
        // ---------------------------------------------------------------------

        return $data;
    }

}
