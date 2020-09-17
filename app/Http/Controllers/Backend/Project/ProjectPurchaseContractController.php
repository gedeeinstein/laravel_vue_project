<?php
// -----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Project;
// -----------------------------------------------------------------------------
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Auth;
// -----------------------------------------------------------------------------
use App\Models\Project;
use App\Models\Company;
use App\Models\CompanyBankAccount;
// -----------------------------------------------------------------------------
use App\Models\MasterValue;
use App\Models\MasterRegion;
// -----------------------------------------------------------------------------
use App\Models\PjPurchaseSale;
use App\Models\PjLotContractor;
use App\Models\PjPurchaseTarget;
use App\Models\PjPurchaseContract;
use App\Models\PjPurchaseContractDetail;
use App\Models\PjPurchaseContractDeposit;
use App\Models\PjPurchaseContractMediation;
use App\Models\PjPurchaseTargetBuilding;
// -----------------------------------------------------------------------------
use App\Models\MasFinance;
use App\Models\MasSection;
// -----------------------------------------------------------------------------
use App\Models\SaleContract;
// -----------------------------------------------------------------------------

class ProjectPurchaseContractController extends Controller
{
    public function __construct()
    {
      $this->project = new Project;
      $this->company = new Company;
      $this->master_value = new MasterValue;
      $this->master_region = new MasterRegion;
      $this->contractor = new PjLotContractor;
      $this->purchase_target = new PjPurchaseTarget;
      $this->purchase_contract = new PjPurchaseContract;
      $this->purchase_contract_detail = new PjPurchaseContractDetail;
      $this->purchase_contract_deposit = new PjPurchaseContractDeposit;
      $this->purchase_contract_mediation = new PjPurchaseContractMediation;
      $this->company_bank_account = new CompanyBankAccount;
      $this->purchase_target_building = new PjPurchaseTargetBuilding;
    }

    // -------------------------------------------------------------------------
    // Validation Rule
    // -------------------------------------------------------------------------
    protected function validator(array $data){
        Validator::make($data, [
            // 'purchase_contract.contract_delivery_money'                => 'numeric',
            // 'purchase_contract.contract_price'                         => 'numeric',
            // 'purchase_contract.contract_deposit'                       => 'numeric',
            // 'purchase_contract.contract_price_building'                => 'regex:/^\d+(\.\d{1,4})?$/',
            // 'purchase_contract.contract_delivery_note'                 => 'max:128',
            // 'purchase_contract.purchase_contract_mediations.*.balance' => 'required',
            // 'purchase_contract.purchase_contract_mediations.*.reward'  => 'regex:/^\d+(\.\d{1,4})?$/',
            // 'purchase_contract.purchase_contract_deposits.*.price'     => 'regex:/^\d+(\.\d{1,4})?$/',
            // 'purchase_contract.purchase_contract_deposits.*.note'      => 'max:128',
        ]);
    }
    // -------------------------------------------------------------------------

    public function contract($id_project)
    {
        // ---------------------------------------------------------------------
        // initial data
        // ---------------------------------------------------------------------
        $data = new \stdClass;
        $data->parcel_city = $this->master_region::pluck('name', 'id');
        $data->building_usetype = $this->master_value::where('type', 'building_usetype')->pluck('value', 'id');
        $data->form_action = route('project.purchase.contract.update', $id_project);
        $data->remove_purchase_project_mediation = route('project.purchase.contract.delete', [
          'project' => $id_project,
          'type' => 'project_purchase_mediation'
        ]);
        $data->remove_purchase_project_deposit = route('project.purchase.contract.delete', [
          'project' => $id_project,
          'type' => 'project_purchase_deposit'
        ]);
        // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------
        // get data project with it's relationship
        // ---------------------------------------------------------------------
        $data->project = $this->project::find($id_project);
        $data->page_title = "仕入契約::{$data->project->title}";
        $user_role = auth()->user()->user_role->name;
        $data->editable = $user_role == 'accounting_firm' ? false : true;
        $project = $this->project::find($id_project);
        $data->purchase_targets = $this->purchase_target::with(['purchase_target_buildings', 'purchase_contract.purchase_contract_mediations', 'purchase_contract.purchase_contract_deposits'])->where('pj_purchase_id', $data->project->purchase->id)->get();
        $data->building_numbers = null;
        // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------
        // redirect 404 if purchase target not found
        // ---------------------------------------------------------------------
        if (count($data->purchase_targets) == 0) abort('404');
        // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------
        // change date format project purchase contract
        // ---------------------------------------------------------------------
        $data = $this->dateFormat($data);
        // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------
        // check sale mediation was inputed
        // 1 mean sale mediation was inputed
        // ---------------------------------------------------------------------
        $sale_mediation_inputed = $data->project->sections->map(function ($section, $key) {
            if ($section->contract)
                return $section->contract->mediations->isEmpty() ? 0 : 1;
            else
                return 0;
        });
        $sale_mediation_inputed = $sale_mediation_inputed->max();
        $data->sale_mediation_inputed = $sale_mediation_inputed ?? 0;
        // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------
        // get real estates data
        // ---------------------------------------------------------------------
        $data->real_estates = $this->company::where('kind_real_estate_agent', '1')->get();
        $data->in_house_n_real_estates = $this->company::where([
            ['kind_in_house', 1],
            ['kind_real_estate_agent', 1],
            ])->get();
        // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------
        // get company bank accounts data
        // ---------------------------------------------------------------------
        // $banks = [];
        // $finance = MasFinance::where('project_id', $project->id)->with('accounts')->first();
        // $accounts = $finance->accounts ?? [];
        // // check if mas finance accounts not empty
        // if (!empty($accounts)) {
        //     // get company bank where in finance accounts
        //     $bank_accounts = CompanyBankAccount::whereIn('id', $accounts->pluck('account_main'))->with(['company', 'bank'])->get();
        //     foreach ($bank_accounts as $bank_account) {
        //         $company = $bank_account->company;
        //         $bank = $bank_account->bank;
        //         $banks[] = [
        //             'id'   => $bank_account->id,
        //             'name' => "{$company->name_abbreviation} {$bank->name_branch_abbreviation} {$bank_account->account_number}"
        //         ];
        //     }
        // }
        // $banks = collect($banks);
        // $data->banks = $banks;

        // ------------------------------------------------------------------
        // Bank Account & Borrower
        // ------------------------------------------------------------------
        // get compnay data with bank account
        // related to purchase sale company organizer
        $company_id_organizer = $data->project->purchaseSale->company_id_organizer ?? null;
        $company = Company::where('id', $company_id_organizer)->first();
        // check if empty data
        if (!isset($company->id)) {
            return $data;
        }
        $bank_accounts = CompanyBankAccount::where('company_id', $company->id)->with(['company', 'bank'])->get();
        // ------------------------------------------------------------------
        $banks = [];
        foreach ($bank_accounts as $bank_account) {
            $company = $bank_account->company;
            $bank = $bank_account->bank;
            $banks[] = [
                'id'   => $bank_account->id,
                'name' => "{$company->name_abbreviation} {$bank->name_branch_abbreviation} {$bank_account->account_number}"
            ];
        }
        $banks = collect($banks);
        $data->banks = $banks;
        // ---------------------------------------------------------------------
        $data->company_bank_accounts = [];
        $data->company_bank_accounts = CompanyBankAccount::whereIn('company_id', $data->in_house_n_real_estates->pluck('id'))
                                       ->with('bank', 'company')->get();
        // ---------------------------------------------------------------------

        // -----------------------------------------------------------------------
        // get data for purchase information block
        // -----------------------------------------------------------------------
        foreach ($data->purchase_targets as $purchase_target_key => $data->purchase_target) {

          $data->target_building[$purchase_target_key] = $this->purchase_target_building::where('pj_purchase_target_id', $data->purchase_target->id)->where('exist_unregistered', '<', 2)->first();
          // initial kind for building
          $data->buildings_no[$purchase_target_key] = $this->purchase_target_building::where('pj_purchase_target_id', $data->purchase_target->id)->where('exist_unregistered', '>', 1)->get();

          $purchase_target_contractors = $data->purchase_target->purchase_target_contractors;
          $purchase_target_contractors_group_by_name[$purchase_target_key] = collect([]);
          foreach ($purchase_target_contractors as $key => $purchase_target_contractor) {
            $same_property = collect([]);
            $same_contractors = $this->contractor::where('name', $purchase_target_contractor->contractor->name)->get();
            foreach ($same_contractors as $key => $same_contractor) {
              if ($same_contractor->common->pj_property_id == $purchase_target_contractor->contractor->common->pj_property_id) {
                $same_property->push($same_contractor);
              }
            }
            $purchase_target_contractors_group_by_name[$purchase_target_key]->push($same_property);
          }
          foreach ($purchase_target_contractors_group_by_name[$purchase_target_key] as $key => $purchase_target_contractors) {
            $i = 0;
            foreach ($purchase_target_contractors as $key => $purchase_target_contractor) {
              $purchase_target_contractor->load('residential_purchase_create', 'road_purchase_create', 'building_purchase_create');
              $purchase_target_contractor['residential'] = $purchase_target_contractor->common->residential_a != null ? $purchase_target_contractor->common->residential_a->load(['residential_owners.property_owner']) : $purchase_target_contractor->common->residential_a;
              $purchase_target_contractor['road'] = $purchase_target_contractor->common->road_a != null ? $purchase_target_contractor->common->road_a->load(['road_owners.property_owner']) : $purchase_target_contractor->common->road_a;
              $purchase_target_contractor['building'] = $purchase_target_contractor->common->building_a != null ? $purchase_target_contractor->common->building_a->load(['building_owners.property_owner']) : $purchase_target_contractor->common->building_a;
              // -----------------------------------------------------------------
              // rowspan calculation for purchase information block
              // -----------------------------------------------------------------
              if ($purchase_target_contractor->common->residential_a) $i++;
              if ($purchase_target_contractor->common->road_a) $i++;
              if ($purchase_target_contractor->common->building_a) $i++;
              // -----------------------------------------------------------------
              if ($purchase_target_contractor->common->building_a) $data->building_numbers[$purchase_target_key][$key] = $purchase_target_contractor['building']->building_number_first."番".$purchase_target_contractor['building']->building_number_second."の".$purchase_target_contractor['building']->building_number_third;
              // $data->building_numbers[$purchase_target_key][$key] = $purchase_target_contractor->common->building_a ? $purchase_target_contractor['building']->building_number_first."番".$purchase_target_contractor['building']->building_number_second."の".$purchase_target_contractor['building']->building_number_third : '';
            }
            $purchase_target_contractors['rowspan'] = $i;
          }
          $data->purchase_target_contractors_group_by_name[$purchase_target_key] = $purchase_target_contractors_group_by_name[$purchase_target_key];          }
          $data->lot_kinds = ['residential', 'road', 'building'];
          // -----------------------------------------------------------------------

        return view('backend.project.purchase-contract.form', (array) $data);
    }

    public function update(Request $reqs, $id_project)
    {
        try {
          $reqs = collect($reqs);
          $pinc = 0;
          foreach ($reqs as $key => $req) {
            // validate request
            // $this->validator($req)->validate();

            // collect data from request purchase contract
            $inputdata = collect($req['purchase_contract']);

            $inputdata['pj_purchase_target_id'] = $req['id'];

            $transaction_result = DB::transaction(function () use ($req, $inputdata, $pinc, $id_project) {
              // ---------------------------------------------------------------
              // create or update pj purchase contract
              // ---------------------------------------------------------------
              if($inputdata['contract_delivery_money'] == null) {
                $inputdata['contract_delivery_money'] = 0;
              }
              $purchase_contract = $this->purchase_contract::updateOrCreate(
                ['id' => $inputdata['id']],
                $inputdata->except(['purchase_contract_mediations', 'purchase_contract_deposits'])->toArray()
              );
              // ---------------------------------------------------------------
              // ---------------------------------------------------------------
              // create or update pj purchase contract mediation
              // ---------------------------------------------------------------
              if ($inputdata['mediation'] == 2) {
                foreach ($inputdata['purchase_contract_mediations'] as $key => $purchase_contract_mediation) {
                  $purchase_contract_mediation['pj_purchase_contract_id'] = $purchase_contract['id'];
                  $purchase_contract_mediation_result = $this->purchase_contract_mediation::updateOrCreate(
                    ['id' => $purchase_contract_mediation['id']],
                    Arr::except($purchase_contract_mediation, ['background'])
                  );
                }
              }elseif ($inputdata['mediation'] == 1) {
                $purchase_contract_mediations = $this->purchase_contract_mediation::where('pj_purchase_contract_id', $purchase_contract['id'])->delete();
              }
              // ---------------------------------------------------------------
              // ---------------------------------------------------------------
              // create or update purchase contract deposit
              // ---------------------------------------------------------------
              $inc = 0;
              foreach ($inputdata['purchase_contract_deposits'] as $key => $purchase_contract_deposit) {
                if($inc == 0) {
                  if($purchase_contract_deposit['status'] == 3) {
                    $pjsale = PjPurchaseSale::where('project_id', $id_project)->first();
                    if($pjsale->project_status > 3 && $pjsale->project_status < 8) {
                      $pjsale->update([
                        'project_status' => 3,
                      ]);
                    }
                  }
                  $inc++; $pinc++;
                }
                $purchase_contract_deposit['pj_purchase_contract_id'] = $purchase_contract['id'];
                $purchase_contract_deposit_result = $this->purchase_contract_deposit::updateOrCreate(
                  ['id' => $purchase_contract_deposit['id']],
                  $purchase_contract_deposit
                );
              }
              // ---------------------------------------------------------------
            });

            $pinc++;
          }
          // -------------------------------------------------------------------
          // response data
          // -------------------------------------------------------------------
          $data = new \stdClass;
          $data->project = Project::find($id_project);
          $data->purchase_targets = $this->purchase_target::with(['purchase_target_buildings', 'purchase_contract.purchase_contract_mediations', 'purchase_contract.purchase_contract_deposits'])->where('pj_purchase_id', $data->project->purchase->id)->get();
          $data->purchase_contract_create = route('project.purchase.target.contract.create', [
              'project' => $id_project,
              'target' => $req['id'],
          ]);
          $data->purchase_response = route('project.purchase.response', ['project' => $id_project, 'purchase_target' => $req['id']]);
          // -------------------------------------------------------------------

          // -------------------------------------------------------------------
          // change date format project purchase contract
          $data = $this->dateFormat($data);
          // -------------------------------------------------------------------

          // -------------------------------------------------------------------
          // success response
          // -------------------------------------------------------------------
          return response()->json([
            'status' => 'success',
            'message' => __('label.success_update_message'),
            'data' => (array) $data,
          ]);
          // -------------------------------------------------------------------

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

    public function delete(Request $req, $id_project, $type)
    {
        switch ($type) {
          case 'project_purchase_mediation':
            $project_purchase_mediation = $this->purchase_contract_mediation::find($req->id)->delete();
            return response()->json([
              'status' => 'success',
              'data' => $project_purchase_mediation,
            ]);
            break;

          case 'project_purchase_deposit':
            $project_purchase_deposit = $this->purchase_contract_deposit::find($req->id)->delete();
            return response()->json([
              'status' => 'success',
              'data' => $project_purchase_deposit,
            ]);
            break;

          default:
            // code...
            break;
        }
    }

    public function dateFormat($data)
    {
      $format = 'Y/m/d';
      foreach ($data->purchase_targets as $key => $purchase_target) {
        // change date format only if purchase contract not null
        // ---------------------------------------------------------------------
        if ($purchase_target->purchase_contract != null) {
            $purchase_target->purchase_contract->contract_date = $purchase_target->purchase_contract->contract_date ? Carbon::parse($purchase_target->purchase_contract->contract_date)->format($format) : '';
            $purchase_target->purchase_contract->contract_payment_date = $purchase_target->purchase_contract->contract_payment_date ? Carbon::parse($purchase_target->purchase_contract->contract_payment_date)->format($format) : '';
            $purchase_target->purchase_contract->contract_delivery_date = $purchase_target->purchase_contract->contract_delivery_date ? Carbon::parse($purchase_target->purchase_contract->contract_delivery_date)->format($format) : '';
          // change date format project purchase mediation
          // -------------------------------------------------------------------
          foreach ($purchase_target->purchase_contract->purchase_contract_mediations as $key => $purchase_contract_mediation) {
              $purchase_contract_mediation->date = $purchase_contract_mediation->date ? Carbon::parse($purchase_contract_mediation->date)->format($format) : null;
          }
          // -------------------------------------------------------------------

          // change date format project purchase deposit
          // -------------------------------------------------------------------
          foreach ($purchase_target->purchase_contract->purchase_contract_deposits as $key => $purchase_contract_deposit) {
                $purchase_contract_deposit->date = $purchase_contract_deposit->date ? Carbon::parse($purchase_contract_deposit->date)->format($format) : null;
          }
          // -------------------------------------------------------------------
        }
        // ---------------------------------------------------------------------
      }

      // -----------------------------------------------------------------------
      return $data;
      // -----------------------------------------------------------------------
    }

}
