<?php

namespace App\Http\Controllers\Backend\Project;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Auth;

// Required Models //
use App\Models\MasterValue;
use App\Models\MasterRegion;
use App\Models\Project;
use App\Models\PjPurchase;
use App\Models\PjPurchaseTarget;
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
use App\Models\PjLotContractor;
use App\Models\PjLotCommon;
use App\Models\PjSheet;
use App\Models\PjStock;
use App\Models\PjStockProcurement;
use App\Models\PjPurchaseContract;
use App\Models\PjPurchaseSale;
use App\Models\TradingLedger;

class ProjectPurchaseController extends Controller
{
    // ----------------------------------------------------------------------
    // Define base data
    // ----------------------------------------------------------------------
    private $route;
    private $project;
    private $property;
    private $property_owners;
    private $residentials_a;
    private $roads_a;
    private $buildings_a;
    private $residential_owners;
    private $road_owners;
    private $building_owners;
    private $master_region;
    private $master_value;
    private $building_floor_size;
    private $purchase;
    private $contractor;
    private $common;
    private $purchase_target;
    private $purchase_target_building;
    private $purchase_target_contractor;
    private $purchase_contract;
    private $purchase_sale;
    private $trading_ledger;

    // ----------------------------------------------------------------------
    // Construct scoped value
    // ----------------------------------------------------------------------
    public function __construct(){
        $this->project              = new Project();
        $this->property             = new PjProperty();
        $this->property_owners      = new PjPropertyOwner();
        $this->residentials_a       = new PjLotResidentialA();
        $this->roads_a              = new PjLotRoadA();
        $this->buildings_a          = new PjLotBuildingA();
        $this->residential_owners   = new PjLotResidentialOwner();
        $this->road_owners          = new PjLotRoadOwner();
        $this->building_owners      = new PjLotBuildingOwner();
        $this->master_region        = new MasterRegion();
        $this->master_value         = new MasterValue();
        $this->building_floor_size  = new PjBuildingFloorSize();
        $this->purchase             = new PjPurchase();
        $this->contractor           = new PjLotContractor();
        $this->common               = new PjLotCommon();
        $this->purchase_target      = new PjPurchaseTarget();
        $this->purchase_target_building     = new PjPurchaseTargetBuilding();
        $this->purchase_target_contractor   = new PjPurchaseTargetContractor();
        $this->purchase_contract            = new PjPurchaseContract();
        $this->purchase_sale                = new PjPurchaseSale();
        $this->trading_ledger               = new TradingLedger();
    }
    // ----------------------------------------------------------------------
    // Project Assist A Page
    // ----------------------------------------------------------------------
    public function form($project_id){
        if($this->purchase::where('project_id', $project_id)->first() == null) {
            $this->purchase::create([
                'count'         => 1,
                'project_id'    => $project_id,
            ]);
        }
        $data                           = new \stdClass;
        // ------------------------------------------------------------------
        // assign data of price
        // ------------------------------------------------------------------
        $pj_sheet       = PjSheet::where('project_id', $project_id)->where('is_reflecting_in_budget', 1)->first();
        if($pj_sheet != null) {
            $pj_stock                   = PjStock::where('pj_sheet_id', $pj_sheet->id)->first();
            $pj_stock_pro               = PjStockProcurement::where('pj_stocking_id', $pj_stock->id)->first();
            $data->price_tsubo_unit     = $pj_stock_pro->price;
        } else {
            $data->price_tsubo_unit     = null;
        }
        $project        = $this->project::find($project_id);
        // ------------------------------------------------------------------
        // assign master data
        // ------------------------------------------------------------------
        $data->page_title               = "仕入買付::{$project->title}";
        $data->project_id               = $project_id;
        $data->project                  = $project;
        $data->ja_kind['residential']   = '宅地';
        $data->ja_kind['road']          = '道路';
        $data->ja_kind['building']      = '建物';
        $data->parcel_city              = $this->master_region::pluck('name', 'id');
        $data->building_usetype         = $this->master_value::where('type', 'building_usetype')->pluck('value', 'id');
        $data->update_url['create_contractor']  = route('project.purchase.contractor.store', $project_id);
        $data->update_url['save_purchase']      = route('project.purchase.store', $project_id);
        $data->update_url['save_count']         = route('project.purchase.count.store', $project_id);
        $data->api_url                          = route('project.purchase.api', $project_id);
        // ------------------------------------------------------------------
        // ※買付数 initial form
        // ------------------------------------------------------------------
        $data->purchase         = $this->purchase::where('project_id', $project_id)->first();
        $target_count = $this->purchase_target::where('pj_purchase_id', $data->purchase->id)->count();
        if($target_count > $data->purchase->count) {
            $data->purchase->update([
                'count' => $target_count,
            ]);
        }
        // ------------------------------------------------------------------
        // 仕入地番ごと契約者 form data
        // ------------------------------------------------------------------
        $pj_property            = $this->property::where('project_id', $project_id)->first();
        $data->pj_property      = $pj_property;
        $data->property_count   = [];
        $data->property_owner   = [];
        $data->commons_list     = [];
        $data->contractors_list = [];
        $data->kind             = [];
        $data->kind_data        = [];
        $data->contractor       = [];
        $data->contractor_same_as_owner = [];
        $data->purchase_targets = [];
        $data->target_building_no_count = 0;
        $used_common = ',';
        // ------------------------------------------------------------------
        if($pj_property != null) {
            $data->commons_list     = $this->common::where('pj_property_id', $pj_property->id)->get();
            $contractorExists       = ',';
            foreach($data->commons_list as $common) {
                $contractor_data = $this->contractor::where('pj_lot_common_id', $common->id)->first();
                if(strpos($contractorExists, $contractor_data->name) === false) {
                    $data->contractors_list[$contractor_data->id] = $contractor_data->name;
                    $contractorExists .= ','.$contractor_data->name;
                }
            }
            $property_owners        = $this->property_owners::where('pj_property_id', $pj_property->id)->get();
            foreach($property_owners as $property_owner) {
                $data->property_owner[$property_owner->id]              = $property_owner;
                $data->kind[$property_owner->id]['residential']         = $this->residential_owners::where('pj_property_owners_id', $property_owner->id)->get();
                $data->kind[$property_owner->id]['road']                = $this->road_owners::where('pj_property_owners_id', $property_owner->id)->get();
                $data->kind[$property_owner->id]['building']            = $this->building_owners::where('pj_property_owners_id', $property_owner->id)->get();
                $data->property_count[$property_owner->id]              = 0;
                if($data->kind[$property_owner->id]['residential']->count() > 0) {
                    foreach($data->kind[$property_owner->id]['residential'] as $key => $kind) {
                        $data->kind_data[$property_owner->id]['residential'][$key]    = $this->residentials_a::find($data->kind[$property_owner->id]['residential']->get($key)->pj_lot_residential_a_id);
                        $get_common                                                   = $this->common::where('pj_property_id', $pj_property->id)->where('pj_lot_residential_a_id', $data->kind[$property_owner->id]['residential']->get($key)->pj_lot_residential_a_id)->get();
                        $common_inc = 0;
                        $common_relation[$key] = null;
                        foreach($get_common as $common_data) {
                            $common_contractor = $this->contractor::where('pj_property_owner_id', $property_owner->id)->where('pj_lot_common_id', $common_data->id)->first();
                            if($common_contractor != null && $common_inc == 0 && strpos($used_common, ','.$common_data->id.',') === false) {
                                $common_relation[$key] = $common_data;
                                $used_common .= $common_data->id.',';
                                $common_inc++;
                            }
                        }
                        if($common_relation[$key] == null) {
                            $data->contractor[$property_owner->id]['residential'][$key]   = $property_owner->name;
                            $data->contractor_same_as_owner[$property_owner->id]['residential'][$key]   = 1;
                            $data->have_common[$property_owner->id]['residential'][$key] = 0;
                        } else {
                            // get data of contractor
                            $current_contractor = $this->contractor::where('pj_lot_common_id', $common_relation[$key]->id)->first();
                            // get data name
                            $data->contractor[$property_owner->id]['residential'][$key] = $current_contractor->name;
                            // get data same to owner
                            $data->contractor_same_as_owner[$property_owner->id]['residential'][$key] = $current_contractor->same_to_owner;
                            // create variable to save existed common relation
                            $data->have_common[$property_owner->id]['residential'][$key] = $common_relation[$key]->id;
                        }
                        $data->property_count[$property_owner->id]++;
                    }
                }
                if($data->kind[$property_owner->id]['road']->count() > 0) {
                    foreach($data->kind[$property_owner->id]['road'] as $key => $kind) {
                        $data->kind_data[$property_owner->id]['road'][$key]           = $this->roads_a::find($data->kind[$property_owner->id]['road']->get($key)->pj_lot_road_a_id);
                        $get_common                                                   = $this->common::where('pj_property_id', $pj_property->id)->where('pj_lot_road_a_id', $data->kind[$property_owner->id]['road']->get($key)->pj_lot_road_a_id)->get();
                        $common_inc = 0;
                        $common_relation[$key] = null;
                        foreach($get_common as $common_data) {
                            $common_contractor = $this->contractor::where('pj_property_owner_id', $property_owner->id)->where('pj_lot_common_id', $common_data->id)->first();
                            if($common_contractor != null && $common_inc == 0 && strpos($used_common, ','.$common_data->id.',') === false) {
                                $common_relation[$key] = $common_data;
                                $used_common .= $common_data->id.',';
                                $common_inc++;
                            }
                        }
                        if($common_relation[$key] == null) {
                            $data->contractor[$property_owner->id]['road'][$key]      = $property_owner->name;
                            $data->contractor_same_as_owner[$property_owner->id]['road'][$key]   = 1;
                            $data->have_common[$property_owner->id]['road'][$key] = 0;
                        } else {
                            // get data of contractor
                            $current_contractor = $this->contractor::where('pj_lot_common_id', $common_relation[$key]->id)->first();
                            // get data name
                            $data->contractor[$property_owner->id]['road'][$key] = $current_contractor->name;
                            // get data same to owner
                            $data->contractor_same_as_owner[$property_owner->id]['road'][$key] = $current_contractor->same_to_owner;
                            // create variable to save existed common relation
                            $data->have_common[$property_owner->id]['road'][$key] = $common_relation[$key]->id;
                        }
                        $data->property_count[$property_owner->id]++;
                    }
                }
                if($data->kind[$property_owner->id]['building']->count() > 0) {
                    foreach($data->kind[$property_owner->id]['building'] as $key => $kind) {
                        $data->kind_data[$property_owner->id]['building'][$key]       = $this->buildings_a::find($data->kind[$property_owner->id]['building']->get($key)->pj_lot_building_a_id);
                        $data->building_floor_size[$property_owner->id][$key]         = $this->building_floor_size::where('pj_lot_building_a_id', $data->kind[$property_owner->id]['building']->get($key)->pj_lot_building_a_id)->sum('floor_size')*1000/1000;
                        $get_common                                                   = $this->common::where('pj_property_id', $pj_property->id)->where('pj_lot_building_a_id', $data->kind[$property_owner->id]['building']->get($key)->pj_lot_building_a_id)->get();
                        $common_inc = 0;
                        $common_relation[$key] = null;
                        foreach($get_common as $common_data) {
                            $common_contractor = $this->contractor::where('pj_property_owner_id', $property_owner->id)->where('pj_lot_common_id', $common_data->id)->first();
                            if($common_contractor != null && $common_inc == 0 && strpos($used_common, ','.$common_data->id.',') === false) {
                                $common_relation[$key] = $common_data;
                                $used_common .= $common_data->id.',';
                                $common_inc++;
                            }
                        }
                        if($common_relation[$key] == null) {
                            $data->contractor[$property_owner->id]['building'][$key]  = $property_owner->name;
                            $data->contractor_same_as_owner[$property_owner->id]['building'][$key]   = 1;
                            $data->have_common[$property_owner->id]['building'][$key] = 0;
                        } else {
                            // get data of contractor
                            $current_contractor = $this->contractor::where('pj_lot_common_id', $common_relation[$key]->id)->first();
                            // get data name
                            $data->contractor[$property_owner->id]['building'][$key] = $current_contractor->name;
                            // get data same to owner
                            $data->contractor_same_as_owner[$property_owner->id]['building'][$key] = $current_contractor->same_to_owner;
                            // create variable to save existed common relation
                            $data->have_common[$property_owner->id]['building'][$key] = $common_relation[$key]->id;
                        }
                        $data->property_count[$property_owner->id]++;
                    }
                }
            }
            // ------------------------------------------------------------------
            // 買付 form data
            // 
            $data->purchase_contractor = []; $Ckey = 0;
            $data->property_owner_list = $this->property_owners::where('pj_property_id', $pj_property->id)->pluck('name', 'id');
            if($data->purchase != null) {
                $data->purchase_targets = $this->purchase_target::where('pj_purchase_id', $data->purchase->id)->get();
                $target_exist = true;
            } else {
                $target_exist = false;
                $data->purchase = new \stdClass;
                $data->purchase->id = null;
                $data->purchase->count = 1;
            }
            if($target_exist) {
                if($data->purchase_targets->count() > 0) {
                    $data->first_price = $data->purchase_targets->get(0)->purchase_price;
                } else {
                    $data->first_price = null;
                }
                foreach($data->purchase_targets as $pkey => $purchase_target) {
                    $data->target_contractor[$purchase_target->id] = $this->purchase_target_contractor::where('pj_purchase_target_id', $purchase_target->id)->get();
                    $data->target_building[$purchase_target->id] = $this->purchase_target_building::where('pj_purchase_target_id', $purchase_target->id)->where('exist_unregistered', '<', 2)->first();
                    // initial kind for building
                    $buildings_no = $this->purchase_target_building::where('pj_purchase_target_id', $purchase_target->id)->where('exist_unregistered', '>', 1)->get();
                    $data->target_building_no_count = $buildings_no->count();
                    foreach($buildings_no as $pkey2 => $building_no) {
                        $data->target_building_no[$pkey+1][$pkey2] = $building_no->kind;
                    }
                    if($data->target_building[$purchase_target->id] == null) {
                        $data->target_building[$purchase_target->id] = $this->purchase_target_building;
                    }
                    foreach($data->target_contractor[$purchase_target->id] as $target_contractor_data) {
                        $data->purchase_contractor_count[$target_contractor_data->id] = 0;
                        $check_contractor = $this->contractor::where('name', $this->contractor::find($target_contractor_data->pj_lot_contractor_id)->name)->get();
                        
                        foreach($check_contractor as $check_contractor_data) {
                            
                            $check_commons = $this->common::find($check_contractor_data->pj_lot_common_id);
                            if($check_commons->pj_property_id == $pj_property->id) {
                                $Ckey++;
                                
                                // get owner of contractor
                                $owner_id = $check_contractor_data->pj_property_id;

                                $data->kindKeyArr[$owner_id]['residential'] = -1;
                                $data->kindKeyArr[$owner_id]['road'] = -1;
                                $data->kindKeyArr[$owner_id]['building'] = -1;

                                if($check_commons->pj_lot_residential_a_id != null) {
                                    $data->purchase_contractor[$purchase_target->id][$target_contractor_data->id][$Ckey][$owner_id]['residential'] = true;
                                } else {
                                    $data->purchase_contractor[$purchase_target->id][$target_contractor_data->id][$Ckey][$owner_id]['residential'] = false;
                                }
                                if($check_commons->pj_lot_road_a_id != null) {
                                    $data->purchase_contractor[$purchase_target->id][$target_contractor_data->id][$Ckey][$owner_id]['road'] = true;
                                    
                                } else {
                                    $data->purchase_contractor[$purchase_target->id][$target_contractor_data->id][$Ckey][$owner_id]['road'] = false;
                                }
                                if($check_commons->pj_lot_building_a_id != null) {
                                    $data->purchase_contractor[$purchase_target->id][$target_contractor_data->id][$Ckey][$owner_id]['building'] = true;
                                    
                                } else {
                                    $data->purchase_contractor[$purchase_target->id][$target_contractor_data->id][$Ckey][$owner_id]['building'] = false;
                                }
                            }
                        }
                        $data->purchase_contractor_count[$target_contractor_data->id] = count($data->purchase_contractor[$purchase_target->id][$target_contractor_data->id]);
                    }
                }
                $data->purchase->data_exist = $data->purchase_targets->count();
            } else {
                $data->purchase_targets = [];
                $data->purchase->data_exist = 0;
            }
        }
        // ------------------------------------------------------------------
        // return data to purchase view
        // ------------------------------------------------------------------
        return view('backend.project.purchase.form', (array)$data);
    }

    public function formApi($project_id){
        $project = $this->project::find($project_id);
        // ------------------------------------------------------------------
        // assign master data
        // ------------------------------------------------------------------
        $data                           = new \stdClass;
        $data->page_title               = "仕入買付::{$project->title}";
        $data->project_id               = $project_id;
        $data->ja_kind['residential']   = '宅地';
        $data->ja_kind['road']          = '道路';
        $data->ja_kind['building']      = '建物';
        $data->parcel_city              = $this->master_region::pluck('name', 'id');
        $data->building_usetype         = $this->master_value::where('type', 'building_usetype')->pluck('value', 'id');
        $data->update_url['create_contractor']  = route('project.purchase.contractor.store', $project_id);
        $data->update_url['save_purchase']      = route('project.purchase.store', $project_id);
        $data->update_url['save_count']         = route('project.purchase.count.store', $project_id);
        $data->api_url                          = route('project.purchase.api', $project_id);
        // ------------------------------------------------------------------
        // ※買付数 initial form
        // ------------------------------------------------------------------
        $data->purchase         = $this->purchase::where('project_id', $project_id)->first();
        // ------------------------------------------------------------------
        // 仕入地番ごと契約者 form data
        // ------------------------------------------------------------------
        $pj_property            = $this->property::where('project_id', $project_id)->first();
        $data->pj_property      = $pj_property;
        $data->property_count   = [];
        $data->property_owner   = [];
        $data->commons_list     = [];
        $data->contractors_list = [];
        $data->kind             = [];
        $data->kind_data        = [];
        $data->contractor       = [];
        $data->contractor_same_as_owner = [];
        $data->purchase_targets = [];
        $used_common = ',';
        // ------------------------------------------------------------------
        if($pj_property != null) {
            $data->commons_list     = $this->common::where('pj_property_id', $pj_property->id)->get();
            $contractorExists       = ',';
            foreach($data->commons_list as $common) {
                $contractor_data = $this->contractor::where('pj_lot_common_id', $common->id)->first();
                if(strpos($contractorExists, $contractor_data->name) === false) {
                    $data->contractors_list[$contractor_data->id] = $contractor_data->name;
                    $contractorExists .= ','.$contractor_data->name;
                }
            }
            $property_owners        = $this->property_owners::where('pj_property_id', $pj_property->id)->get();
            foreach($property_owners as $property_owner) {
                $data->property_owner[$property_owner->id]              = $property_owner;
                $data->kind[$property_owner->id]['residential']         = $this->residential_owners::where('pj_property_owners_id', $property_owner->id)->get();
                $data->kind[$property_owner->id]['road']                = $this->road_owners::where('pj_property_owners_id', $property_owner->id)->get();
                $data->kind[$property_owner->id]['building']            = $this->building_owners::where('pj_property_owners_id', $property_owner->id)->get();
                $data->property_count[$property_owner->id]              = 0;
                if($data->kind[$property_owner->id]['residential']->count() > 0) {
                    foreach($data->kind[$property_owner->id]['residential'] as $key => $kind) {
                        $data->kind_data[$property_owner->id]['residential'][$key]    = $this->residentials_a::find($data->kind[$property_owner->id]['residential']->get($key)->pj_lot_residential_a_id);
                        $get_common                                                   = $this->common::where('pj_property_id', $pj_property->id)->where('pj_lot_residential_a_id', $data->kind[$property_owner->id]['residential']->get($key)->pj_lot_residential_a_id)->get();
                        $common_inc = 0;
                        $common_relation[$key] = null;
                        foreach($get_common as $common_data) {
                            if($common_inc == 0 && strpos($used_common, ','.$common_data->id.',') === false) {
                                $common_relation[$key] = $common_data;
                                $used_common .= $common_data->id.',';
                                $common_inc++;
                            }
                        }
                        if($common_relation[$key] == null) {
                            $data->contractor[$property_owner->id]['residential'][$key]   = $property_owner->name;
                            $data->contractor_same_as_owner[$property_owner->id]['residential'][$key]   = 1;
                        } else {
                            // get data of contractor
                            $current_contractor = $this->contractor::where('pj_lot_common_id', $common_relation[$key]->id)->first();
                            // get data name
                            $data->contractor[$property_owner->id]['residential'][$key] = $current_contractor->name;
                            // get data same to owner
                            $data->contractor_same_as_owner[$property_owner->id]['residential'][$key] = $current_contractor->same_to_owner;
                        }
                        $data->property_count[$property_owner->id]++;
                    }
                }
                if($data->kind[$property_owner->id]['road']->count() > 0) {
                    foreach($data->kind[$property_owner->id]['road'] as $key => $kind) {
                        $data->kind_data[$property_owner->id]['road'][$key]           = $this->roads_a::find($data->kind[$property_owner->id]['road']->get($key)->pj_lot_road_a_id);
                        $get_common                                                   = $this->common::where('pj_property_id', $pj_property->id)->where('pj_lot_road_a_id', $data->kind[$property_owner->id]['road']->get($key)->pj_lot_road_a_id)->get();
                        $common_inc = 0;
                        $common_relation[$key] = null;
                        foreach($get_common as $common_data) {
                            if($common_inc == 0 && strpos($used_common, ','.$common_data->id.',') === false) {
                                $common_relation[$key] = $common_data;
                                $used_common .= $common_data->id.',';
                                $common_inc++;
                            }
                        }
                        if($common_relation[$key] == null) {
                            $data->contractor[$property_owner->id]['road'][$key]      = $property_owner->name;
                            $data->contractor_same_as_owner[$property_owner->id]['road'][$key]   = 1;
                        } else {
                            // get data of contractor
                            $current_contractor = $this->contractor::where('pj_lot_common_id', $common_relation[$key]->id)->first();
                            // get data name
                            $data->contractor[$property_owner->id]['road'][$key] = $current_contractor->name;
                            // get data same to owner
                            $data->contractor_same_as_owner[$property_owner->id]['road'][$key] = $current_contractor->same_to_owner;
                        }
                        $data->property_count[$property_owner->id]++;
                    }
                }
                if($data->kind[$property_owner->id]['building']->count() > 0) {
                    foreach($data->kind[$property_owner->id]['building'] as $key => $kind) {
                        $data->kind_data[$property_owner->id]['building'][$key]       = $this->buildings_a::find($data->kind[$property_owner->id]['building']->get($key)->pj_lot_building_a_id);
                        $data->building_floor_size[$property_owner->id][$key]         = $this->building_floor_size::where('pj_lot_building_a_id', $data->kind[$property_owner->id]['building']->get($key)->pj_lot_building_a_id)->sum('floor_size')*1000/1000;
                        $get_common                                                   = $this->common::where('pj_property_id', $pj_property->id)->where('pj_lot_building_a_id', $data->kind[$property_owner->id]['building']->get($key)->pj_lot_building_a_id)->get();
                        $common_inc = 0;
                        $common_relation[$key] = null;
                        foreach($get_common as $common_data) {
                            if($common_inc == 0 && strpos($used_common, ','.$common_data->id.',') === false) {
                                $common_relation[$key] = $common_data;
                                $used_common .= $common_data->id.',';
                                $common_inc++;
                            }
                        }
                        if($common_relation[$key] == null) {
                            $data->contractor[$property_owner->id]['building'][$key]  = $property_owner->name;
                            $data->contractor_same_as_owner[$property_owner->id]['building'][$key]   = 1;
                        } else {
                            // get data of contractor
                            $current_contractor = $this->contractor::where('pj_lot_common_id', $common_relation[$key]->id)->first();
                            // get data name
                            $data->contractor[$property_owner->id]['building'][$key] = $current_contractor->name;
                            // get data same to owner
                            $data->contractor_same_as_owner[$property_owner->id]['building'][$key] = $current_contractor->same_to_owner;
                        }
                        $data->property_count[$property_owner->id]++;
                    }
                }
            }
            // ------------------------------------------------------------------
            // 買付 form data
            // 
            $data->purchase_contractor = []; $Ckey = 0;
            $data->property_owner_list = $this->property_owners::where('pj_property_id', $pj_property->id)->pluck('name', 'id');
            if($data->purchase != null) {
                $data->purchase_targets = $this->purchase_target::where('pj_purchase_id', $data->purchase->id)->get();
                $target_exist = true;
            } else {
                $target_exist = false;
                $data->purchase = new \stdClass;
                $data->purchase->id = null;
                $data->purchase->count = 1;
            }
            if($target_exist) {
                foreach($data->purchase_targets as $purchase_target) {
                    $data->target_contractor[$purchase_target->id] = $this->purchase_target_contractor::where('pj_purchase_target_id', $purchase_target->id)->get();
                    $data->target_building[$purchase_target->id] = $this->purchase_target_building::where('pj_purchase_target_id', $purchase_target->id)->where('exist_unregistered', '<', 2)->first();
                    if($data->target_building[$purchase_target->id] == null) {
                        $data->target_building[$purchase_target->id] = $this->purchase_target_building;
                    }
                    foreach($data->target_contractor[$purchase_target->id] as $target_contractor_data) {
                        $data->purchase_contractor_count[$target_contractor_data->id] = 0;
                        $check_contractor = $this->contractor::where('name', $this->contractor::find($target_contractor_data->pj_lot_contractor_id)->name)->get();
                        
                        foreach($check_contractor as $check_contractor_data) {
                            
                            $check_commons = $this->common::find($check_contractor_data->pj_lot_common_id);
                            if($check_commons->pj_property_id == $pj_property->id) {
                                $Ckey++;
                                
                                $owner_id = $check_contractor_data->pj_property_owner;

                                $data->kindKeyArr[$owner_id]['residential'] = -1;
                                $data->kindKeyArr[$owner_id]['road'] = -1;
                                $data->kindKeyArr[$owner_id]['building'] = -1;

                                if($check_commons->pj_lot_residential_a_id != null) {
                                    $data->purchase_contractor[$purchase_target->id][$target_contractor_data->id][$Ckey][$owner_id]['residential'] = true;
                                } else {
                                    $data->purchase_contractor[$purchase_target->id][$target_contractor_data->id][$Ckey][$owner_id]['residential'] = false;
                                }
                                if($check_commons->pj_lot_road_a_id != null) {
                                    $data->purchase_contractor[$purchase_target->id][$target_contractor_data->id][$Ckey][$owner_id]['road'] = true;
                                    
                                } else {
                                    $data->purchase_contractor[$purchase_target->id][$target_contractor_data->id][$Ckey][$owner_id]['road'] = false;
                                }
                                if($check_commons->pj_lot_building_a_id != null) {
                                    $data->purchase_contractor[$purchase_target->id][$target_contractor_data->id][$Ckey][$owner_id]['building'] = true;
                                    
                                } else {
                                    $data->purchase_contractor[$purchase_target->id][$target_contractor_data->id][$Ckey][$owner_id]['building'] = false;
                                }
                            }
                        }
                        $data->purchase_contractor_count[$target_contractor_data->id] = count($data->purchase_contractor[$purchase_target->id][$target_contractor_data->id]);
                    }
                }
                $data->purchase->data_exist = $data->purchase_targets->count();
            } else {
                $data->purchase_targets = [];
                $data->purchase->data_exist = 0;
            }
        } 
        // ------------------------------------------------------------------
        // return data to purchase view
        // ------------------------------------------------------------------
        return response()->json($data);
    }

    public function save_count(Request $request) {
        $data = $request->all();
        $current_purchase = $this->purchase::updateOrCreate([
            'project_id' => $data['project_id'],
            ],[
            'count' => $data['count'],
        ]);
        $purchase_targets = $this->purchase_target::where('pj_purchase_id', $current_purchase->id)->get();
        for($x = $data['count']; $x < $purchase_targets->count(); $x++) {
            $this->trading_ledger::where('pj_purchase_target_id', $purchase_targets->get($x)->id)->delete();
            $this->purchase_contract::where('pj_purchase_target_id', $purchase_targets->get($x)->id)->delete();
            $this->purchase_target_building::where('pj_purchase_target_id', $purchase_targets->get($x)->id)->delete();
            $this->purchase_target_contractor::where('pj_purchase_target_id', $purchase_targets->get($x)->id)->delete();
            $this->purchase_target::find($purchase_targets->get($x)->id)->delete();
        }

        return redirect()->route('project.purchase', $data['project_id']);
    }

    public function save_contractors(Request $request) {
        $data = $request->all();
        if($this->common::where('pj_property_id', $data['property_id'])->count() > 0) {
            $contractor_exist =  true;
        } else {
            $contractor_exist =  false;
        }
        if($contractor_exist) {
            foreach($data['contractor_name'] as $key => $contractor_name) {
                foreach($contractor_name as $keyKind => $contractor_kinds) {
                    foreach($contractor_kinds as $keyKind2 => $contractor_kind) {
                        if($keyKind == 'residential') {
                            $kind = 'pj_lot_residential_a_id';
                            $kind2 = 'pj_lot_road_a_id';
                            $kind3 = 'pj_lot_building_a_id';
                        } else
                        if($keyKind == 'road') {
                            $kind = 'pj_lot_road_a_id';
                            $kind2 = 'pj_lot_residential_a_id';
                            $kind3 = 'pj_lot_building_a_id';
                        } else
                        if($keyKind == 'building') {
                            $kind = 'pj_lot_building_a_id';
                            $kind2 = 'pj_lot_road_a_id';
                            $kind3 = 'pj_lot_residential_a_id';
                        }
                        
                        $update_contractor = $this->contractor::where('pj_lot_common_id', $data['have_common'][$key][$keyKind][$keyKind2])->first();
                        if($update_contractor != null) {

                            $prev_name = $update_contractor->name;

                            $update_contractor->update([
                                'name'  => $contractor_kind,
                                'same_to_owner' => $data['contractor_same_to_owner'][$key][$keyKind][$keyKind2],
                                'pj_property_owner_id' => $data['property_owners'][$key]['id'],
                            ]);

                            if($prev_name != $contractor_kind) {
                                $update_target = $this->purchase_target_contractor::where('pj_lot_contractor_id', $update_contractor->id)->first();
                                if($update_target != null) {
                                    $new_target = $this->contractor::where('pj_property_owner_id', $data['property_owners'][$key]['id'])
                                                                    ->where('name', $prev_name)->first();
                                    if($new_target != null) {
                                        $update_target->update([
                                            'pj_lot_contractor_id' => $new_target->id,
                                        ]);
                                    } else {
                                        $update_target->delete();
                                    }
                                }
                            }

                        } else {
                            $new_common = $this->common::create([
                                'pj_property_id' => $data['property_id'],
                                $kind => $data['common'][$key][$keyKind][$keyKind2][$kind],
                                $kind2 => null,
                                $kind3 => null,
                            ]);
    
                            $this->contractor::create([
                                'name'  => $contractor_kind,
                                'same_to_owner' => $data['contractor_same_to_owner'][$key][$keyKind][$keyKind2],
                                'pj_lot_common_id' => $new_common->id,
                                'pj_property_owner_id' => $data['property_owners'][$key]['id'],
                            ]);
                        }
                    }
                }
            }
        } else {
            foreach($data['contractor_name'] as $key => $contractor_name) {
                foreach($contractor_name as $keyKind => $contractor_kinds) {
                    foreach($contractor_kinds as $keyKind2 => $contractor_kind) {
                        if($keyKind == 'residential') {
                            $kind = 'pj_lot_residential_a_id';
                            $kind2 = 'pj_lot_road_a_id';
                            $kind3 = 'pj_lot_building_a_id';
                        } else
                        if($keyKind == 'road') {
                            $kind = 'pj_lot_road_a_id';
                            $kind2 = 'pj_lot_residential_a_id';
                            $kind3 = 'pj_lot_building_a_id';
                        } else
                        if($keyKind == 'building') {
                            $kind = 'pj_lot_building_a_id';
                            $kind2 = 'pj_lot_road_a_id';
                            $kind3 = 'pj_lot_residential_a_id';
                        }
                        
                        $new_common = $this->common::create([
                            'pj_property_id' => $data['property_id'],
                            $kind => $data['common'][$key][$keyKind][$keyKind2][$kind],
                            $kind2 => null,
                            $kind3 => null,
                        ]);

                        $this->contractor::create([
                            'name'  => $contractor_kind,
                            'same_to_owner' => $data['contractor_same_to_owner'][$key][$keyKind][$keyKind2],
                            'pj_lot_common_id' => $new_common->id,
                            'pj_property_owner_id' => $data['property_owners'][$key]['id'],
                        ]);
                        
                    }
                }
            }
        }

        return redirect()->route('project.purchase', $data['project_id']);
    }

    public function save_purchases(Request $request) {
        $data = $request->all();
        $purchase_target = 0;
        // -------------------------------------------------------------------
        // save purchase card data
        // -------------------------------------------------------------------
        $purchase_targets = $this->purchase_target::where('pj_purchase_id', $data['purchase_id'])->get();
        $new_target_count = count($data['target_contractor']);
        if($new_target_count < $purchase_targets->count()) {
            $limit_data = $new_target_count;
        } else {
            $limit_data = $purchase_targets->count();
        }
        $current_purchase = $this->purchase::where('project_id', $data['project_id'])->first();
        $current_purchase->update([
            'count' => $new_target_count,
        ]);
        for($i = 0; $i < $limit_data; $i++) {
            // purchase target update
            if(array_key_exists('purchase_not_create_documents', $data)) {
                if(array_key_exists($i+1, $data['purchase_not_create_documents'])) {
                    $purchase_not_create_documents = true;
                } else {
                    $purchase_not_create_documents = false;
                }
            } else {
                $purchase_not_create_documents = false;
            }
            if($data['purchase_deposit'][$i+1] == null) {
                $data['purchase_deposit'][$i+1] = 0;
            }
            $purchase_price = str_replace('.', '', $data['purchase_price'][$i+1]);
            $purchase_price = str_replace(',', '', $data['purchase_price'][$i+1]);
            $purchase_deposit = str_replace('.', '', $data['purchase_deposit'][$i+1]);
            $purchase_deposit = str_replace(',', '', $data['purchase_deposit'][$i+1]);
            $target = $this->purchase_target::find($purchase_targets->get($i)->id);
            $target->update([
                'purchase_price'                    => $purchase_price,
                'purchase_deposit'                  => $purchase_deposit,
                'purchase_not_create_documents'     => $purchase_not_create_documents,
            ]);
            // purchase target building update
            if(array_key_exists('exist_unregistered', $data)) {
                if(array_key_exists($i+1, $data['exist_unregistered'])) {
                    $exist_unregistered = true;
                    if(array_key_exists('building_kind', $data)) {
                        if(array_key_exists($i+1, $data['building_kind'])) {
                            $kind = $data['building_kind'][$i+1];
                        } else {
                            $kind = null;
                        }
                    } else {
                        $kind = null;
                    }
                } else {
                    $exist_unregistered = false;
                    $kind = null;
                }
            } else {
                $exist_unregistered = false;
                $kind = null;
            }
            if(array_key_exists('third_person', $data)) {
                if(array_key_exists($i+1, $data['third_person'])) {
                    $third_person = $data['third_person'][$i+1];
                } else {
                    $third_person = null;
                }
            } else {
                $third_person = null;
            }

            if($this->purchase_target_building::where('pj_purchase_target_id', $purchase_targets->get($i)->id)->where('exist_unregistered', '<=', 1)->first() == null) {
                $this->purchase_target_building::create([
                    'pj_purchase_target_id'     => $purchase_targets->get($i)->id,
                    'kind'                      => $kind,
                    'exist_unregistered'        => $exist_unregistered,
                    'purchase_third_person_occupied' => $third_person,
                ]);
            } else {
                $this->purchase_target_building::where('pj_purchase_target_id', $purchase_targets->get($i)->id)->where('exist_unregistered', '<=', 1)->update([
                    'kind'                      => $kind,
                    'exist_unregistered'        => $exist_unregistered,
                    'purchase_third_person_occupied' => $third_person,
                ]);
            }
            

            // update data for building number information row
            $previous_building_no   = $this->purchase_target_building::where('pj_purchase_target_id', $purchase_targets->get($i)->id)->where('exist_unregistered', '>', 1)->get();
            if(array_key_exists('building_no', $data)) {
                if(array_key_exists($i+1, $data['building_no'])) {
                    $new_building_no_count  = count($data['building_no'][$i+1]);
                } else {
                    $new_building_no_count  = 0;
                }
            } else {
                $new_building_no_count  = 0;
            }
            for($bn=0; $bn<$new_building_no_count; $bn++) {
                if($bn < $previous_building_no->count()) {
                    $current_no = $previous_building_no->get($bn);
                    $current_no->update([
                        'kind'                      => $data['building_no'][$i+1][$bn],
                        'exist_unregistered'        => $bn+2,
                        'purchase_third_person_occupied' => null,
                    ]);
                } else {
                    $this->purchase_target_building::create([
                        'pj_purchase_target_id'     => $purchase_targets->get($i)->id,
                        'kind'                      => $data['building_no'][$i+1][$bn],
                        'exist_unregistered'        => $bn+2,
                        'purchase_third_person_occupied' => null,
                    ]);
                }
            }
            // ------------------------------------
            // update building for purchase contract
            // ------------------------------------
            if($this->purchase_contract::where('pj_purchase_target_id', $purchase_targets->get($i)->id)->first() == null) {
                if($new_building_no_count > 0) {
                    $this->purchase_contract->pj_purchase_target_id     = $purchase_targets->get($i)->id;
                    $this->purchase_contract->contract_building_number  = $data['building_no_val'];
                    $this->purchase_contract->contract_building_kind                       = $data['building_no'][$i+1][$bn-1];
                    $this->purchase_contract->contract_building_unregistered               = $exist_unregistered;
                    $this->purchase_contract->contract_building_unregistered_kind          = $kind;
                    $this->purchase_contract->save();
                } else {
                    $this->purchase_contract->pj_purchase_target_id     = $purchase_targets->get($i)->id;
                    $this->purchase_contract->contract_building_unregistered               = $exist_unregistered;
                    $this->purchase_contract->contract_building_unregistered_kind          = $kind;
                    $this->purchase_contract->save();
                }
            } else {
                if($new_building_no_count > 0) {
                    $this->purchase_contract::where('pj_purchase_target_id', $purchase_targets->get($i)->id)->update([
                        'contract_building_number'              => $data['building_no_val'],
                        'contract_building_kind'                => $data['building_no'][$i+1][$bn-1],
                        'contract_building_unregistered'        => $exist_unregistered,
                        'contract_building_unregistered_kind'   => $kind,
                    ]);
                } else {
                    $this->purchase_contract::where('pj_purchase_target_id', $purchase_targets->get($i)->id)->update([
                        'contract_building_number'              => null,
                        'contract_building_kind'                => null,
                        'contract_building_unregistered'        => $exist_unregistered,
                        'contract_building_unregistered_kind'   => $kind,
                    ]);
                }
            }
            
            // delete building number kind if new less than previous data
            $this->purchase_target_building::where('pj_purchase_target_id', $purchase_targets->get($i)->id)->where('exist_unregistered', '>=', $new_building_no_count+2)->delete();
            // purchase target contractor update
            $pur_contractors = $this->purchase_target_contractor::where('pj_purchase_target_id', $purchase_targets->get($i)->id)->get();
            $y = 0;
            foreach($data['target_contractor'][$i+1] as $contractor) {
                if($pur_contractors->count() > $y) {
                    $update_contractor = $pur_contractors->get($y);
                    $update_contractor->update([
                        'pj_lot_contractor_id'      => $contractor,
                        'user_id'                   => Auth::user()->id,
                    ]);
                } else {
                    $this->purchase_target_contractor::create([
                        'pj_lot_contractor_id'      => $contractor,
                        'pj_purchase_target_id'     => $purchase_targets->get($i)->id,
                        'user_id'                   => Auth::user()->id,
                    ]);
                }
                $y++;
            }
            if($pur_contractors->count() > count($data['target_contractor'][$i+1])) {
                for($z = $y; $z < $pur_contractors->count(); $z++) {
                    $pur_contractors->get($z)->delete();
                }
            }
            if(array_key_exists('x_submit', $data)) {
                if($data['x_submit'] == $i+1) {
                    $purchase_target = $purchase_targets->get($i)->id;
                    $this->purchase_target_contractor::where('pj_purchase_target_id', $purchase_targets->get($i)->id)->update([
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }
        }
        if($purchase_targets->count() < $new_target_count) {
            for($x = $purchase_targets->count(); $x < $new_target_count; $x++) {
                $key = $x+1;
                if(array_key_exists('purchase_not_create_documents', $data)) {
                    if(array_key_exists($key, $data['purchase_not_create_documents'])) {
                        $purchase_not_create_documents = true;
                    } else {
                        $purchase_not_create_documents = false;
                    }
                } else {
                    $purchase_not_create_documents = false;
                }
                if(array_key_exists('exist_unregistered', $data)) {
                    if(array_key_exists($key, $data['exist_unregistered'])) {
                        $exist_unregistered = true;
                        $kind = $data['building_kind'][$key];
                    } else {
                        $exist_unregistered = false;
                        $kind = null;
                    }
                } else {
                    $exist_unregistered = false;
                    $kind = null;
                }
                if(array_key_exists('third_person', $data)) {
                    if(array_key_exists($key, $data['third_person'])) {
                        $third_person = $data['third_person'][$key];
                    } else {
                        $third_person = null;
                    }
                } else {
                    $third_person = null;
                }
                if($data['purchase_deposit'][$key] == null) {
                    $data['purchase_deposit'][$key] = 0;
                }
                $purchase_price = str_replace('.', '', $data['purchase_price'][$key]);
                $purchase_price = str_replace(',', '', $data['purchase_price'][$key]);
                $purchase_deposit = str_replace('.', '', $data['purchase_deposit'][$key]);
                $purchase_deposit = str_replace(',', '', $data['purchase_deposit'][$key]);
                $target = $this->purchase_target::create([
                    'pj_purchase_id'                    => $data['purchase_id'],
                    'purchase_price'                    => $purchase_price,
                    'purchase_deposit'                  => $purchase_deposit,
                    'purchase_not_create_documents'     => $purchase_not_create_documents,
                ]);
                foreach($data['target_contractor'][$key] as $contractor) {
                    $this->purchase_target_contractor::create([
                        'pj_lot_contractor_id'      => $contractor,
                        'pj_purchase_target_id'     => $target->id,
                        'user_id'                   => Auth::user()->id,
                    ]);
                }
                $this->purchase_target_building::create([
                    'pj_purchase_target_id'     => $target->id,
                    'kind'                      => $kind,
                    'exist_unregistered'        => $exist_unregistered,
                    'purchase_third_person_occupied' => $third_person,
                ]);
                // ------------------------------------
                // create new building no kind data
                // ------------------------------------
                if(array_key_exists('building_no', $data)) {
                    if(array_key_exists($key, $data['building_no'])) {
                        $new_building_no_count  = count($data['building_no'][$key]);
                    } else {
                        $new_building_no_count  = 0;
                    }
                } else {
                    $new_building_no_count  = 0;
                }
                for($bn=0; $bn<$new_building_no_count; $bn++) {
                    $this->purchase_target_building::create([
                        'pj_purchase_target_id'     => $target->id,
                        'kind'                      => $data['building_no'][$key][$bn],
                        'exist_unregistered'        => $bn+2,
                        'purchase_third_person_occupied' => null,
                    ]);
                }
                // ------------------------------------

                // ------------------------------------
                // create new building for purchase contract
                // ------------------------------------
                if($new_building_no_count > 0) {
                    $this->purchase_contract->pj_purchase_target_id     = $target->id;
                    $this->purchase_contract->contract_building_number  = $data['building_no_val'];
                    $this->purchase_contract->contract_building_kind                       = $data['building_no'][$key][$bn-1];
                    $this->purchase_contract->contract_building_unregistered               = $exist_unregistered;
                    $this->purchase_contract->contract_building_unregistered_kind          = $kind;
                    $this->purchase_contract->save();
                } else {
                    $this->purchase_contract->pj_purchase_target_id     = $target->id;
                    $this->purchase_contract->contract_building_unregistered               = $exist_unregistered;
                    $this->purchase_contract->contract_building_unregistered_kind          = $kind;
                    $this->purchase_contract->save();
                }
                // ------------------------------------

                // ------------------------------------
                // create new trading ledger for each purchase target
                // ------------------------------------
                $current_sale = $this->purchase_sale::where('project_id', $data['project_id'])->first();
                $this->trading_ledger::create([
                    'pj_purchase_target_id' => $target->id,
                    'pj_purchase_sale_id'   => $current_sale->id,
                    'sale_contract_id'      => null,
                    'ledger_type'           => 1,
                    'status'                => 1,
                ]);
                // ------------------------------------

                if(array_key_exists('x_submit', $data)) {
                    if($data['x_submit'] == $key) {
                        $purchase_target = $target->id;
                    }
                }
            }
        } else {
            for($x = $new_target_count; $x < $purchase_targets->count(); $x++) {
                $this->trading_ledger::where('pj_purchase_target_id', $purchase_targets->get($x)->id)->delete();
                $this->purchase_contract::where('pj_purchase_target_id', $purchase_targets->get($x)->id)->delete();
                $this->purchase_target_building::where('pj_purchase_target_id', $purchase_targets->get($x)->id)->delete();
                $this->purchase_target_contractor::where('pj_purchase_target_id', $purchase_targets->get($x)->id)->delete();
                $this->purchase_target::find($purchase_targets->get($x)->id)->delete();
            }
        }
        // -------------------------------------------------------------------

        
        return response()->json([
            'newUrl'  => route('project.purchase.create', [$data['project_id'], $purchase_target]),
        ]);
    }
}
