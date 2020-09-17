<?php
// -----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Project;
// -----------------------------------------------------------------------------
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
// -----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Auth;
// -----------------------------------------------------------------------------
use App\Models\MasterValue;
use App\Models\MasterRegion;
// -----------------------------------------------------------------------------
use App\Models\Project;
// -----------------------------------------------------------------------------
use App\Models\PjProperty;
use App\Models\PjLotContractor;
use App\Models\PjLotRoadPurchaseCreate;
use App\Models\PjLotBuildingPurchaseCreate;
use App\Models\PjLotResidentialPurchaseCreate;
// -----------------------------------------------------------------------------
use App\Models\PjPurchase;
use App\Models\PjPurchaseSale;
use App\Models\PjPurchaseTarget;
use App\Models\PjPurchaseDoc;
use App\Models\PjPurchaseDocOptionalMemo;
// -----------------------------------------------------------------------------
use App\Models\RequestInspection as Inspection;
// -----------------------------------------------------------------------------
use App\Reports\PurchaseContractChecklistReport;
use App\Reports\PurchaseContractReport;
use App\Http\Controllers\Backend\Project\ProjectInspectionController;
// -----------------------------------------------------------------------------
class ProjectPurchaseCreateController extends Controller
{
    public function __construct()
    {
        $this->project = new Project;
        $this->purchase_target = new PjPurchaseTarget;
        $this->purchase_doc = new PjPurchaseDoc;
        $this->purchase_doc_optional_memo = new PjPurchaseDocOptionalMemo;
        $this->residential_purchase_create = new PjLotResidentialPurchaseCreate;
        $this->road_purchase_create = new PjLotRoadPurchaseCreate;
        $this->building_purchase_create = new PjLotBuildingPurchaseCreate;
        $this->contractor = new PjLotContractor;
        $this->master_region = new MasterRegion();
        $this->master_value  = new MasterValue();
        $this->property = new PjProperty;
    }

    // -------------------------------------------------------------------------
    // Validation Rule
    // -------------------------------------------------------------------------
    protected function validator(array $data){
      return Validator::make($data, [
        'purchase_target_contractors_group_by_name.*.*.residential_purchase_create.purchase_equity_text' => 'max:128',
        'purchase_target_contractors_group_by_name.*.*.road_purchase_create.purchase_equity_text' => 'max:128',
        'purchase_target_contractors_group_by_name.*.*.building_purchase_create.purchase_equity_text' => 'max:128',
        'purchase_doc.heads_up_memo'                    => 'max:128',
        'purchase_doc.properties_description_memo'      => 'max:128',
        'purchase_doc.front_road_f'                     => 'max:128',
        'purchase_doc.front_road_memo'                  => 'max:128',
        'purchase_doc.contract_memo'                    => 'max:128',
        'purchase_doc.gathering_request_to'             => 'max:128',
        'purchase_doc.desired_contract_date'            => 'max:128',
        'purchase_doc.settlement_date'                  => 'max:128',
        'purchase_doc.gathering_request_memo'           => 'max:128',
        'purchase_doc_optional_memo.content'            => 'max:128',
      ]);
    }
    // -------------------------------------------------------------------------

    public function purchase_create($project_id, $purchase_target_id)
    {
      // -----------------------------------------------------------------------
      // initial data
      // -----------------------------------------------------------------------
      $data = new \stdClass;
      $data->parcel_city = $this->master_region::pluck('name', 'id');
      $data->building_usetype = $this->master_value::where('type', 'building_usetype')->pluck('value', 'id');
      $data->agreement_list = route('project.purchase.create.agreement-list', $project_id);
      $data->remove_purchase_doc_optional_memo = route('project.purchase.create.delete', $project_id);
      $data->purchase_target = $this->purchase_target::findOrFail($purchase_target_id);
      $data->project = $data->purchase_target->purchase->project;
      $data->page_title = "仕入買付作成::{$data->project->title}";
      $data->form_action = route('project.purchase.create.update', [
        'project' => $project_id,
        'purchase_target' => $purchase_target_id,
      ]);
      if ($data->purchase_target ==  null) abort('404');
      $data->purchase_doc = $data->purchase_target->purchase_doc;
      $data->purchase_doc_optional_memos = $data->purchase_doc != null ? $data->purchase_doc->optional_memos : [];
      $data->contract_checklist_report = route('project.purchase.create.create.report', [
        'project' => $project_id,
        'target' => $purchase_target_id,
      ]);
      $data->contract_contract_report = route('project.purchase.create.contract.report', [
        'project' => $project_id,
        'target' => $purchase_target_id,
      ]);
      // -----------------------------------------------------------------------

      // -----------------------------------------------------------------------
      // change date format
      // -----------------------------------------------------------------------
      if ($data->purchase_doc){
          $data->purchase_doc->expiration_date = $data->purchase_doc->expiration_date ?
            Carbon::parse($data->purchase_doc->expiration_date)->format('Y/m/d') : null;
      }
      // -----------------------------------------------------------------------

      // -------------------------------------------------------------------
      // set purcahase number
      // -------------------------------------------------------------------
      $data->purchase_targets = $data->purchase_target->purchase->purchase_targets;
      if (count($data->purchase_targets) == 0) $purchase_description_a->push(1);
      foreach ($data->purchase_targets as $key => $purchase_target) {
        if ($purchase_target->id == $data->purchase_target->id) {
          $data->purchase_target['purchase_number'] = $key + 1;
        }
      }

      // -----------------------------------------------------------------------
      // get data for purchase information block
      // -----------------------------------------------------------------------
      $purchase_target_contractors = $data->purchase_target->purchase_target_contractors;
      $purchase_target_contractors_group_by_name = collect([]);
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
      // -----------------------------------------------------------------------
      $urbanization_area_sub = null;
      $purchase_sale['urbanization_area'] = null;
      $purchase_sale['urbanization_area_sub_1'] = null;
      $purchase_sale['urbanization_area_sub_2'] = null;
      $data->purchase_third_person_occupied = null;
      $purchase_description_a = collect([1]);
      // -----------------------------------------------------------------------
      foreach ($purchase_target_contractors_group_by_name as $k => $purchase_target_contractors) {
        $i = 0;
        foreach ($purchase_target_contractors as $key => $purchase_target_contractor) {
            $purchase_target_contractor->load('residential_purchase_create', 'road_purchase_create', 'building_purchase_create');
            $purchase_target_contractor['residential'] = $purchase_target_contractor->common->residential_a != null ? $purchase_target_contractor->common->residential_a->load(['residential_owners.property_owner', 'residential_purchase']) : $purchase_target_contractor->common->residential_a;
            $purchase_target_contractor['road'] = $purchase_target_contractor->common->road_a != null ? $purchase_target_contractor->common->road_a->load(['road_owners.property_owner', 'road_purchase']) : $purchase_target_contractor->common->road_a;
            $purchase_target_contractor['building'] = $purchase_target_contractor->common->building_a != null ? $purchase_target_contractor->common->building_a->load(['building_owners.property_owner']) : $purchase_target_contractor->common->building_a;
            // -----------------------------------------------------------------
            // condition for purchase sale
            // -----------------------------------------------------------------
            if ($purchase_target_contractor->common->residential_a != null && $purchase_sale['urbanization_area'] == null && $purchase_target_contractor->common->residential_a->residential_purchase != null)
            $purchase_sale['urbanization_area'] = $purchase_target_contractor->common->residential_a->residential_purchase->urbanization_area == 3 ? 1 : null;
            if ($purchase_target_contractor->common->road_a != null && $purchase_sale['urbanization_area'] == null && $purchase_target_contractor->common->road_a->road_purchase != null)
            $purchase_sale['urbanization_area'] = $purchase_target_contractor->common->road_a->road_purchase->urbanization_area == 3 ? 1 : null;

            if ($purchase_target_contractor->common->residential_a != null && $purchase_sale['urbanization_area_sub_1'] == null && $purchase_target_contractor->common->residential_a->residential_purchase != null)
            $purchase_sale['urbanization_area_sub_1'] = $purchase_target_contractor->common->residential_a->residential_purchase->urbanization_area == 3 && $purchase_target_contractor->common->residential_a->residential_purchase->urbanization_area_sub == 1 ? 1 : null;
            if ($purchase_target_contractor->common->road_a != null && $purchase_sale['urbanization_area_sub_1'] == null && $purchase_target_contractor->common->road_a->road_purchase != null)
            $purchase_sale['urbanization_area_sub_1'] = $purchase_target_contractor->common->road_a->road_purchase->urbanization_area == 3 && $purchase_target_contractor->common->road_a->road_purchase->urbanization_area_sub == 1 ? 1 : null;

            if ($purchase_target_contractor->common->residential_a != null && $purchase_sale['urbanization_area_sub_2'] == null && $purchase_target_contractor->common->residential_a->residential_purchase != null)
            $purchase_sale['urbanization_area_sub_2'] = $purchase_target_contractor->common->residential_a->residential_purchase->urbanization_area == 3 && $purchase_target_contractor->common->residential_a->residential_purchase->urbanization_area_sub == 2 ? 1 : null;
            if ($purchase_target_contractor->common->road_a != null && $purchase_sale['urbanization_area_sub_2'] == null && $purchase_target_contractor->common->road_a->road_purchase != null)
            $purchase_sale['urbanization_area_sub_2'] = $purchase_target_contractor->common->road_a->road_purchase->urbanization_area == 3 && $purchase_target_contractor->common->road_a->road_purchase->urbanization_area_sub == 2 ? 1 : null;
            // -----------------------------------------------------------------

            // -----------------------------------------------------------------
            // condition for purchase third person occupied
            // set value for purchase_description_a
            // -----------------------------------------------------------------
            foreach ($data->purchase_target->purchase_target_buildings as $key => $building) {
                if ($data->purchase_third_person_occupied == null) { $data->purchase_third_person_occupied = $building->purchase_third_person_occupied; }

                count($data->purchase_target->purchase_target_buildings) == 0 ? $purchase_description_a->push(1) : '';
                $building->kind == 2 ? $purchase_description_a->push(2) : '';
                $building->kind == 1 ? $purchase_description_a->push(3) : '';
            }
            // -----------------------------------------------------------------

            // -----------------------------------------------------------------
            // rowspan calculation for purchase information block
            // -----------------------------------------------------------------
            if ($purchase_target_contractor->common->residential_a) $i++;
            if ($purchase_target_contractor->common->road_a) $i++;
            if ($purchase_target_contractor->common->building_a) $i++;
            // -----------------------------------------------------------------
        }
        $purchase_target_contractors['rowspan'] = $i;
      }      

      // check purchase description a collection value and find one value needed
      // -----------------------------------------------------------------------
      $building_kind_2 = $purchase_description_a->contains(2);
      $building_kind_1 = $purchase_description_a->contains(3);
      if ($building_kind_2 && $building_kind_1) $purchase_description_a->push(4);
      $data->purchase_description_a = $purchase_description_a->max();
      // -----------------------------------------------------------------------

      $data->purchase_target_contractors_group_by_name = $purchase_target_contractors_group_by_name;
      $data->purchase_sale = $purchase_sale;
      $data->lot_kinds = ['residential', 'road', 'building'];
      // -----------------------------------------------------------------------

      // ------------------------------------------------------------------
      // Project inspection request
      // ------------------------------------------------------------------
      $query = Inspection::with([ 'user' ])->where( 'kind', 2 )
             ->where( 'active', true )
             ->where( 'project_id', $project_id )
             ->orderBy( 'created_at', 'desc' );
      $data->inspection = $query->first();

      $data->inspection_url = null;
      if ($data->inspection) {
          $data->inspection_url = route('project.inspection.update', $data->inspection->id);
          $data->inspection['user_fullname'] = $data->inspection->user->full_name;
          $data->inspection['request_date'] = Carbon::parse($data->inspection->request_datetime)->format('m/d');
          $data->inspection['request_hour'] = Carbon::parse($data->inspection->request_datetime)->format('H:i');
      }
      // ------------------------------------------------------------------

      return view('backend.project.purchase-create.form', (array) $data);
    }

    public function update($project_id, $purchase_target_id, Request $request)
    {
      $this->validator($request->all())->validate();

      try {
        DB::transaction(function () use($request) {
          $purchase_doc_input = collect($request->purchase_doc);
          // ---------------------------------------------------------------------
          // create or update purchase doc data
          // ---------------------------------------------------------------------
          $purchase_doc = $this->purchase_doc::updateOrCreate(
              ['id' => $purchase_doc_input['id']],
              $purchase_doc_input->except('optional_memos')->toArray()
          );
          // ---------------------------------------------------------------------

          // ---------------------------------------------------------------------
          // create or update purchase doc optional memo data
          // ---------------------------------------------------------------------
          foreach ($request->purchase_doc_optional_memos as $key => $purchase_doc_optional_memo) {
            // if ($purchase_doc_optional_memo['content'] != null) {
              $purchase_doc_optional_memo['pj_purchase_doc_id'] = $purchase_doc->id;
              $purchase_doc_optional_memo = $this->purchase_doc_optional_memo::updateOrCreate(
                ['id' => $purchase_doc_optional_memo['id']],
                $purchase_doc_optional_memo
              );
            // }
          }
          // ---------------------------------------------------------------------

          // ---------------------------------------------------------------------
          // create or update pj_lot_*_purchase_create
          // ---------------------------------------------------------------------
          foreach ($request->purchase_target_contractors_group_by_name as $key => $purchase_target_contractors) {
            foreach ($purchase_target_contractors as $key => $purchase_target_contractor) {
              if ($purchase_target_contractor['residential_purchase_create'] != null) {
                  $purchase_target_contractor['residential_purchase_create']['pj_lot_residential_a_id'] = $purchase_target_contractor['residential']['id'];
                if ($purchase_target_contractor['residential_purchase_create']['pj_lot_residential_a_id'])
                {
                    $residential_purchase_create = $this->residential_purchase_create::updateOrCreate(
                      ['id' => $purchase_target_contractor['residential_purchase_create']['id']],
                      $purchase_target_contractor['residential_purchase_create']
                    );
                }
              }
              if ($purchase_target_contractor['road_purchase_create'] != null) {
                  $purchase_target_contractor['road_purchase_create']['pj_lot_road_a_id'] = $purchase_target_contractor['road']['id'];
                  if ($purchase_target_contractor['road_purchase_create']['pj_lot_road_a_id'])
                  {
                      $road_purchase_create = $this->road_purchase_create::updateOrCreate(
                        ['id' => $purchase_target_contractor['road_purchase_create']['id']],
                        $purchase_target_contractor['road_purchase_create']
                      );
                  }
              }
              if ($purchase_target_contractor['building_purchase_create'] != null) {
                  $purchase_target_contractor['building_purchase_create']['pj_lot_building_a_id'] = $purchase_target_contractor['building']['id'];
                  if ($purchase_target_contractor['building_purchase_create']['pj_lot_building_a_id'])
                  {
                      $building_purchase_create = $this->building_purchase_create::updateOrCreate(
                        ['id' => $purchase_target_contractor['building_purchase_create']['id']],
                        $purchase_target_contractor['building_purchase_create']
                      );
                  }
              }
            }
          }
          // ---------------------------------------------------------------------
        });

        // ---------------------------------------------------------------------
        // check inspection request
        // ---------------------------------------------------------------------
        if ($request->initial['submited_index'] == 3) {
          $purchase_target = $this->purchase_target::find($purchase_target_id);
          $purchase_doc = $purchase_target->purchase_doc;
          $approval_request = $this->approval_request($purchase_doc);

          // create inspection
          $purchase_target = $this->purchase_target::find($purchase_target_id);
          $project = $purchase_target->purchase->project;
          $data = new Request;
          $data->project  = $project_id;
          $data->type = 2;
          $inspection = app('App\Http\Controllers\Backend\Project\ProjectInspectionController')->request( $data );
        }
        // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------
        // set response data
        // ---------------------------------------------------------------------
        $response_data = new \stdClass;
        $response_data->purchase_target = $this->purchase_target::find($purchase_target_id);
        $response_data->project = $response_data->purchase_target->purchase->project;
        $response_data->purchase_doc = $response_data->purchase_target->purchase_doc;

        // change date format
        // ---------------------------------------------------------------------
        if ($response_data->purchase_doc){
            $response_data->purchase_doc->expiration_date = $response_data->purchase_doc->expiration_date ?
              Carbon::parse($response_data->purchase_doc->expiration_date)->format('Y/m/d') : null;
        }
        // ---------------------------------------------------------------------

        $response_data->purchase_doc_optional_memos = $response_data->purchase_doc->optional_memos;
          // -------------------------------------------------------------------
          // response data for purchase information block
          // -------------------------------------------------------------------
          $purchase_target_contractors = $response_data->purchase_target->purchase_target_contractors;
          $purchase_target_contractors_group_by_name = collect([]);
          foreach ($purchase_target_contractors as $key => $purchase_target_contractor) {
            $same_property = collect([]);
            $same_contractors = $this->contractor::where('name', $purchase_target_contractor->contractor->name)->get();
            foreach ($same_contractors as $key => $same_contractor) {
              if ($same_contractor->common->pj_property_id == $purchase_target_contractor->contractor->common->pj_property_id) {
                $same_property->push($same_contractor);
              }
            }
            $purchase_target_contractors_group_by_name->push($same_property);
          }
          $purchase_description_a = collect([]);
          foreach ($purchase_target_contractors_group_by_name as $key => $purchase_target_contractors) {
            foreach ($purchase_target_contractors as $key => $purchase_target_contractor) {
              $purchase_target_contractor->load('residential_purchase_create', 'road_purchase_create', 'building_purchase_create');
              $purchase_target_contractor['residential'] = $purchase_target_contractor->common->residential_a != null ? $purchase_target_contractor->common->residential_a->load(['residential_owners.property_owner']) : $purchase_target_contractor->common->residential_a;
              $purchase_target_contractor['road'] = $purchase_target_contractor->common->road_a != null ? $purchase_target_contractor->common->road_a->load(['road_owners.property_owner']) : $purchase_target_contractor->common->road_a;
              $purchase_target_contractor['building'] = $purchase_target_contractor->common->building_a != null ? $purchase_target_contractor->common->building_a->load(['building_owners.property_owner']) : $purchase_target_contractor->common->building_a;
            }
          }
          $response_data->purchase_target_contractors_group_by_name = $purchase_target_contractors_group_by_name;
          // -------------------------------------------------------------------

          // ------------------------------------------------------------------
          // inspection response
          // ------------------------------------------------------------------
          $query = Inspection::with([ 'user' ])->where( 'kind', 2 )
                 ->where( 'active', true )
                 ->where( 'project_id', $project_id )
                 ->orderBy( 'created_at', 'desc' );
          $response_data->inspection = $query->first();

          $response_data->inspection_url = null;
          if ($response_data->inspection) {
              $response_data->inspection_url = route('project.inspection.update', $response_data->inspection->id);
              $response_data->inspection['user_fullname'] = $response_data->inspection->user->full_name;
              $response_data->inspection['request_date'] = Carbon::parse($response_data->inspection->request_datetime)->format('m/d');
              $response_data->inspection['request_hour'] = Carbon::parse($response_data->inspection->request_datetime)->format('H:i');
          }
          // ------------------------------------------------------------------
        // ---------------------------------------------------------------------
        // ---------------------------------------------------------------------

        // success response
        // ---------------------------------------------------------------------
        if ($request->initial['submited_index'] == 1) $message = "買付証明書を出力中です、しばらくお待ちください。";
        elseif ($request->initial['submited_index'] == 2) $message = __('label.report_downloaded');
        else $message = __('label.success_update_message');

        return response()->json([
          'status' => 'success',
          'message' => $message,
          'data' => (array) $response_data
        ]);
        // ---------------------------------------------------------------------
      } catch (\Exception $error) {
        // -------------------------------------------------------------------
        // error response
        // -------------------------------------------------------------------
        Log::error([
            'message'   => $error->getMessage(),
            'file'      => $error->getFile().':'.$error->getLine(),
            'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
        ]);

        return response()->json([
          'status' => 'error',
          'message' => __('label.failed_update_message'),
          'error' => $error->getMessage(),
        ], 500);
        // -------------------------------------------------------------------
      }

    }

    public function delete(Request $request)
    {
        try {
          $purchase_doc_optional_memo = $this->purchase_doc_optional_memo::find($request->id)->delete();
          // -------------------------------------------------------------------
          // succes response
          // -------------------------------------------------------------------
          return response()->json([
            'status' => 'success',
            'message' => __('label.success_delete_message'),
            'data' => $purchase_doc_optional_memo,
          ]);
          // -------------------------------------------------------------------
        } catch (\Exception $e) {
          // -------------------------------------------------------------------
          // error response
          // -------------------------------------------------------------------
          Log::error([
              'message'   => $error->getMessage(),
              'file'      => $error->getFile().':'.$error->getLine(),
              'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
          ]);

          return response()->json([
            'status' => 'error',
            'message' => __('label.failed_delete_message'),
            'error' => $error->getMessage(),
          ], 500);
          // -------------------------------------------------------------------
        }
    }

    public function approval_request($purchase_doc)
    {
      $user = Auth::user();
      $project = $purchase_doc->purchase_target->purchase->project;
      $project->approval_request = 1;
      $project->save();

      return $project;
    }

    // ----------------------------------------------------------------------
    // Handle report checklist requests
    // ----------------------------------------------------------------------
    public function report( Request $request ){
        // ------------------------------------------------------------------
        $data = new \stdClass;
        $response = (object) array( 'status' => 'success' );
        // ------------------------------------------------------------------
        $projectID = (int) $request->project;
        $targetID = (int) $request->target;

        // ------------------------------------------------------------------
        // Get data for create report output of checklist
        // ------------------------------------------------------------------
        $data->project = $this->project::find($projectID);
        $data->purchase_doc = $this->purchase_doc::where('pj_purchase_target_id', $targetID)->first();
        $data->property = $this->property::with('pj_lot_residential_a.residential_purchase', 'pj_lot_road_a.road_purchase', 'pj_lot_residential_a.residential_b', 'pj_lot_road_a.road_b')->where('project_id', $projectID)->first();
        // ------------------------------------------------------------------

        // ----------------------------------------------------------
        // Generate the report
        // ----------------------------------------------------------
        $filepath = PurchaseContractChecklistReport::reportPurchaseContractChecklist($data);
        $response->report = $filepath;
        $response->filename = basename( $filepath );
        // ----------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json( (array) $response );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Handle report contract requests
    // ----------------------------------------------------------------------
    public function reportContract( Request $request ){
        // ------------------------------------------------------------------
        $data = new \stdClass;
        $response = (object) array( 'status' => 'success' );
        // ------------------------------------------------------------------
        $projectID = (int) $request->project;
        $targetID = (int) $request->target;

        // ------------------------------------------------------------------
        // Get data for create report output of contract
        // ------------------------------------------------------------------
        $data->project = $this->project::find($projectID);
        $data->purchase_target = $this->purchase_target::find($targetID);
        // ------------------------------------------------------------------

        // ----------------------------------------------------------
        // Generate the report
        // ----------------------------------------------------------
        $filepath = PurchaseContractReport::reportPurchaseContract($data, $targetID);
        $response->report = $filepath;
        $response->filename = basename( $filepath );
        // ----------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json( (array) $response );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    public function agreement_list($project_id)
    {
        $data = new \stdClass;
        $data->project = $this->project::find($project_id);
        $data->page_title = "仕入買付作成::{$data->project->title}";

        return view('backend.project.purchase-create.form.agreement-list', (array) $data);
    }
}
