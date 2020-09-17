<?php
// -----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Project;
// -----------------------------------------------------------------------------
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\LogActivityTrait;
use Auth;
// -----------------------------------------------------------------------------
use App\Models\MasterValue as Value;
use App\Models\MasterRegion as Region;
use App\Models\PjPurchaseDoc as Doc;
use App\Models\PjLotContractor as Contractor;
use App\Models\PjPurchaseTarget as Target;
use App\Models\PjPurchaseResponse as Response;
// -----------------------------------------------------------------------------

class ProjectPurchaseResponseController extends Controller
{
    use LogActivityTrait;

    // Validation Rule
    // -------------------------------------------------------------------------
    protected function validator(array $data){
      return Validator::make($data, [
        'notices_a_text'                         => 'max:128',
        'notices_b_text'                         => 'max:128',
        'notices_c_text'                         => 'max:128',
        'notices_d_text'                         => 'max:128',
        'notices_e_text'                         => 'max:128',
        'desired_contract_terms_m_text'          => 'max:128',
        'desired_contract_terms_r_1_text'        => 'max:128',
        'desired_contract_terms_r_2_text'        => 'max:128',
      ]);
    }
    // -------------------------------------------------------------------------

    public function response($project_id, $purchase_target_id)
    {
        try {
            $data = $this->source_data($project_id, $purchase_target_id);

            return view('backend.project.purchase-response.form', (array) $data);
        } catch (\Exception $error) {
            abort('404');
        }
    }

    public function store($project_id, $purchase_target_id, Request $request)
    {
        // validate request
        $this->validator($request->purchase_response)->validate();

        try {
            $input_purchase_response = $request->purchase_response;
            $input_purchase_response['pj_purchase_target_id'] = $request->purchase_target['id'];

            // -----------------------------------------------------------------
            // store data to database
            // -----------------------------------------------------------------
            $purchase_response = Response::updateOrCreate(
                [ 'id' => $input_purchase_response['id'],
                    'pj_purchase_target_id' => $request->purchase_target['id']
                ],
                $input_purchase_response
            );
            // -----------------------------------------------------------------

            // -----------------------------------------------------------------
            // set response data
            // -----------------------------------------------------------------
            $response_data = $this->source_data($project_id, $purchase_target_id);
            // -----------------------------------------------------------------

            // -----------------------------------------------------------------
            // success response
            // -----------------------------------------------------------------
            return response()->json([
                'status'  => 'success',
                'message' => __('label.success_update_message'),
                'data' => $response_data,
            ], 200);
            // -----------------------------------------------------------------

        } catch (\Exception $error) {
            // -----------------------------------------------------------------
            // error response
            // -----------------------------------------------------------------
            Log::error([
                'message'   => $error->getMessage(),
                'file'      => $error->getFile().':'.$error->getLine(),
                'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
            ]);
            // -----------------------------------------------------------------
            return response()->json([
                'status'  => 'error',
                'message' => __('label.failed_update_message'),
                'error'   => $error->getMessage()
            ], 500);
            // -----------------------------------------------------------------
        }
    }

    public function source_data($project_id, $purchase_target_id)
    {
        // ---------------------------------------------------------------------
        // set initial data
        // ---------------------------------------------------------------------
        $initial = collect([
            'target'   => factory(Target::class)->states('init')->make(),
            'doc'      => factory(Doc::class)->states('init')->make(),
            'response' => factory(Response::class)->states('init')->make(),
        ]);
        // ---------------------------------------------------------------------

        // -----------------------------------------------------------------
        // get data for response page
        // -----------------------------------------------------------------
        $parcel_city = Region::pluck('name', 'id');
        $building_usetype = Value::where('type', 'building_usetype')->pluck('value', 'id');
        // -----------------------------------------------------------------
        $purchase_target = Target::find($purchase_target_id);
        $purchase_doc = $purchase_target->purchase_doc;
        $purchase_response = $purchase_target->purchase_response;
        $purchase_contract_create = $purchase_target->purchase_contract != null ?
                                    $purchase_target->purchase_contract->purchase_contract_create : null;
        // -----------------------------------------------------------------
        $project = $purchase_target->purchase->project;
        $page_title = "仕入買付応否入力::{$project->title}";
        $form_action = route('project.purchase.response.store', [
            'project' => $project->id,
            'purchase_target' => $purchase_target_id
        ]);
        // -----------------------------------------------------------------

        // -----------------------------------------------------------------
        // set purcahase number
        // -----------------------------------------------------------------
        $purchase_targets = $purchase_target->purchase->purchase_targets;
        foreach ($purchase_targets as $key => $target) {
            if ($purchase_target->id == $target->id) {
                $purchase_target['purchase_number'] = $key + 1;
            }
        }
        // -----------------------------------------------------------------

        // -----------------------------------------------------------------
        // get data for purchase information block
        // -----------------------------------------------------------------
        $purchase_target_contractors = $purchase_target->purchase_target_contractors;
        $purchase_target_contractors_group_by_name = collect([]);
        foreach ($purchase_target_contractors as $key => $purchase_target_contractor) {
            $same_property = collect([]);
            $same_contractors = Contractor::where('name', $purchase_target_contractor->contractor->name)->get();
            foreach ($same_contractors as $key => $same_contractor) {
                if ($same_contractor->common->pj_property_id == $purchase_target_contractor->contractor->common->pj_property_id) {
                    $same_property->push($same_contractor);
                }
            }
            $purchase_target_contractors_group_by_name->push($same_property);
        }

        foreach ($purchase_target_contractors_group_by_name as $key => $purchase_target_contractors) {
            $i = 0;
            foreach ($purchase_target_contractors as $key => $purchase_target_contractor) {
                $purchase_target_contractor['residential'] = $purchase_target_contractor->common->residential_a;
                $purchase_target_contractor['road'] = $purchase_target_contractor->common->road_a;
                $purchase_target_contractor['building'] = $purchase_target_contractor->common->building_a;
                $purchase_target_contractor['doc'] = $purchase_doc;

                // rowspan calculation for purchase information block
                // -------------------------------------------------------------
                if ($purchase_target_contractor->common->residential_a) $i++;
                if ($purchase_target_contractor->common->road_a) $i++;
                if ($purchase_target_contractor->common->building_a) $i++;
                // -------------------------------------------------------------
            }
            $purchase_target_contractors['rowspan'] = $i;
        }
        $lot_kinds = ['residential', 'road', 'building'];
        // -----------------------------------------------------------------

        // assign data
        // -----------------------------------------------------------------
        $data = new \stdClass;
        // -----------------------------------------------------------------
        $data->initial                  = $initial;
        // -----------------------------------------------------------------
        $data->parcel_city              = $parcel_city;
        $data->building_usetype         = $building_usetype;
        // -----------------------------------------------------------------
        $data->purchase_target          = $purchase_target;
        $data->purchase_doc             = $purchase_doc;
        $data->purchase_response        = $purchase_response;
        $data->purchase_contract_create = $purchase_contract_create;
        $data->purchase_targets         = $purchase_targets;
        // -----------------------------------------------------------------
        $data->project                  = $project;
        $data->page_title               = $page_title;
        $data->form_action              = $form_action;
        // -----------------------------------------------------------------
        $data->lot_kinds                = $lot_kinds;
        $data->purchase_target_contractors_group_by_name = $purchase_target_contractors_group_by_name;
        // -----------------------------------------------------------------
        
        // -----------------------------------------------------------------
        return $data;
        // -----------------------------------------------------------------
    }
}
