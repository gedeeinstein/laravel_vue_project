<?php

namespace App\Http\Controllers\Backend\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
// -----------------------------------------------------------------------------
use App\Models\MasterValue as Value;
use App\Models\MasterRegion as Region;
// -----------------------------------------------------------------------------
use App\Models\User as User;
use App\Models\Company as Company;
use App\Models\CompanyBankAccount as Account;
// -----------------------------------------------------------------------------
use App\Models\PjProperty as Property;
use App\Models\PjLotBuildingA as BuildingA;
// -----------------------------------------------------------------------------
use App\Models\MasSection as Section;
use App\Models\MasFinance as Finance;
// -----------------------------------------------------------------------------
use App\Models\SaleContract as Contract;
use App\Models\SaleContractDeposit as Deposit;
use App\Models\SaleMediation as Mediation;
use App\Models\SaleFee as Fee;
use App\Models\SaleFeePrice as Price;
use App\Models\SalePurchase as Purchase;
use App\Models\SaleProductResidence as Residence;
// -----------------------------------------------------------------------------
use App\Models\RequestInspection as Inspection;
// -----------------------------------------------------------------------------

class SaleContractController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->source_data($request->section);

        // set sale purchase active tab
        // ---------------------------------------------------------------------
        $data->active_tab = 0;
        if ($request->purchase) {
            foreach ($data->sale_contract->purchases as $key => $purchase) {
                if ($purchase->id == (int) $request->purchase) {
                    $data->active_tab = $key;
                }
            }
        }
        // ---------------------------------------------------------------------

        return view('backend.sale.contract.form', (array) $data);
    }

    public function update(Request $request)
    {
        try {
            // save and update process using DB transaction
            // -----------------------------------------------------------------
            DB::transaction(function () use ($request) {
                // get request data
                // -------------------------------------------------------------
                $relations = array(
                    'purchases', 'deposits', 'mediations',
                    'residences', 'fee', 'fee.prices'
                );
                // -------------------------------------------------------------
                $sale_contract = collect($request->sale_contract)->except($relations);
                $purchases     = $request->purchases;
                $deposits      = $request->deposits;
                $mediations    = $request->mediations;
                $residences    = $request->residences;
                $fee           = collect($request->fee)->except('prices');
                $prices        = $request->prices;
                // -------------------------------------------------------------
                $inspections   = $request->inspections;
                // -------------------------------------------------------------
                $purchase_active    = $request->purchase_active;
                $inspection_clicked = $request->inspection_clicked;
                $abolished_clicked  = $request->abolished_clicked;
                // -------------------------------------------------------------

                // save and update sale contract data
                // -------------------------------------------------------------
                $sale_contract = Contract::updateOrCreate(
                    ['id' => $sale_contract['id']],
                    $sale_contract->toArray()
                );
                // -------------------------------------------------------------

                // save and update sale purchase data
                // -------------------------------------------------------------
                foreach ($purchases as $key => $purchase) {
                    $purchase['sale_contract_id'] = $sale_contract->id;
                    $purchase = Purchase::updateOrCreate(
                        ['id' => $purchase['id']],
                        $purchase
                    );

                    // inspection request or abolished request
                    // ---------------------------------------------------------
                    if (($inspection_clicked || $abolished_clicked) && $key == $purchase_active) {
                        if ($inspection_clicked) {
                            // request data
                            // -------------------------------------------------
                            $data           = new Request;
                            $data->project  = (int) $request->project;
                            $data->type     = 4;
                            $data->context  = $purchase->id;
                            // -------------------------------------------------
                            app('App\Http\Controllers\Backend\Project\ProjectInspectionController')->request($data);
                            // -------------------------------------------------
                        }elseif ($abolished_clicked) {
                            $purchase->accept_result = 4;
                            $purchase->save();
                        }
                    }
                    // ---------------------------------------------------------
                }
                // -------------------------------------------------------------

                // save and update sale contract deposit data
                // -------------------------------------------------------------
                foreach ($deposits as $key => $deposit) {
                    $deposit['sale_contract_id'] = $sale_contract->id;
                    Deposit::updateOrCreate(
                        ['id' => $deposit['id']],
                        $deposit
                    );
                }
                // -------------------------------------------------------------

                // save and update sale mediation data
                // delete mediation data if mediation enable = 1
                // -------------------------------------------------------------
                foreach ($mediations as $key => $mediation) {
                    if ($mediation['enable'] == 2) {
                        $mediation['sale_contract_id'] = $sale_contract->id;
                        Mediation::updateOrCreate(
                            ['id' => $mediation['id']],
                            $mediation
                        );
                    }elseif ($mediation['enable'] == 1) {
                        Mediation::where('sale_contract_id', $sale_contract->id)->delete();
                    }
                }
                // -------------------------------------------------------------

                // -------------------------------------------------------------

                // save and update sale product residence data
                // -------------------------------------------------------------
                foreach ($residences as $key => $residence) {
                    $residence['sale_contract_id'] = $sale_contract->id;
                    Residence::updateOrCreate(
                        ['id' => $residence['id']],
                        $residence
                    );
                }
                // -------------------------------------------------------------

                // save and update sale fee data
                // delete fee and fee price data if fee enable = 1
                // -------------------------------------------------------------
                if ($fee['enable'] == 2) {
                    $fee['sale_contract_id'] = $sale_contract->id;
                    $fee = Fee::updateOrCreate(
                        ['id' => $fee['id']],
                        $fee->toArray()
                    );
                }elseif ($fee['enable'] == 1) {
                    Price::where('sale_fee_id', $fee['id'])->delete();
                    Fee::where('sale_contract_id', $sale_contract->id)->delete();
                }
                // -------------------------------------------------------------

                // save and update sale fee price data if fee enable == 2
                // -------------------------------------------------------------
                if ($fee['enable'] == 2) {
                    foreach ($prices as $key => $price) {
                        $price['sale_contract_id'] = $sale_contract->id;
                        $price['sale_fee_id'] = $fee->id;
                        Price::updateOrCreate(
                            ['id' => $price['id']],
                            $price
                        );
                    }
                }
                // -------------------------------------------------------------

                // update inspection request data
                // -------------------------------------------------------------
                foreach ($inspections as $key => $inspection) {
                    if ($inspection['id']) {
                        $data = Inspection::find($inspection['id']);
                        $data->update([
                            'examination'      => $inspection['examination'],
                            'examination_text' => $inspection['examination_text']
                        ]);
                    }
                }
                // -------------------------------------------------------------
            });
            // -----------------------------------------------------------------

            // update inspection request active value
            // -----------------------------------------------------------------
            $query = Inspection::where('kind', 4)
                ->where('project_id', $request->project)
                ->orderBy('created_at', 'desc')
                ->get();
            $query = $query->groupBy('context');

            foreach ($query as $key => $inspections) {
                foreach ($inspections as $key => $inspection) {
                    if ($key == 0) $inspection->update([ 'active' => 1 ]);
                }
            }
            // -----------------------------------------------------------------

            // set success response
            // -----------------------------------------------------------------
            $response_data = $this->source_data($request->section);

            return response()->json([
                'status' => 'success',
                'message' => __('label.success_update_message'),
                'data' => $response_data
            ]);
            // -----------------------------------------------------------------
        } catch (\Exception $error) {
            // log error and return error message
            // --------------------------------------------------------------
            Log::error([
                'message'   => $error->getMessage(),
                'file'      => $error->getFile().':'.$error->getLine(),
                'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
            ]);
            // --------------------------------------------------------------

            // set error response
            // -----------------------------------------------------------------
            return response()->json([
                'status'  => 'error',
                'message' => __('label.failed_update_message'),
                'error'   => $error->getMessage()
            ], 500);
            // -----------------------------------------------------------------
        }
    }

    public function delete(Request $request, $project_id, $section_id, $type)
    {
        try {
            if ($type == 'purchases')
                Purchase::find($request->id)->delete();
            else if ($type == 'deposits')
                Deposit::find($request->id)->delete();
            else if ($type == 'mediations')
                Mediation::find($request->id)->delete();
            else if ($type == 'prices')
                Price::find($request->id)->delete();

            return response()->json([
                'status'  => 'success',
                'message' => __('label.success_delete_message'),
            ]);

        } catch (\Exception $error) {
            // log error and return error message
            // --------------------------------------------------------------
            Log::error([
                'message'   => $error->getMessage(),
                'file'      => $error->getFile().':'.$error->getLine(),
                'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
            ]);
            // --------------------------------------------------------------

            // set error response
            // -----------------------------------------------------------------
            return response()->json([
                'status'  => 'error',
                'message' => __('label.failed_delete_message'),
                'error'   => $error->getMessage()
            ], 500);
            // -----------------------------------------------------------------
        }

    }

    public function source_data($id)
    {
        // ---------------------------------------------------------------------
        // get data for sale contract page
        // ---------------------------------------------------------------------

        // get master data
        // ---------------------------------------------------------------------
        $master_values  = Value::all()->keyBy('id');
        $master_regions = Region::all()->keyBy('id');
        // ---------------------------------------------------------------------

        // get data user and user restriction
        // ---------------------------------------------------------------------
        $users    = User::all()->keyBy('id');
        $editable = Auth::user()->user_role->name == 'accounting_firm' ? false : true;
        $role     = Auth::user()->user_role->name;
        // ---------------------------------------------------------------------

        $companies = Company::all(); // get data company

        // create initial data for sale contract relation
        // ---------------------------------------------------------------------
        $initial = collect([
            'purchases'   => factory(Purchase::class)->states('init')->make(),
            'deposits'    => factory(Deposit::class)->states('init')->make(),
            'mediations'  => factory(Mediation::class)->states('init')->make(),
            'residences'  => factory(Residence::class)->states('init')->make(),
            'fee'         => factory(Fee::class)->states('init')->make(),
            'prices'      => factory(Price::class)->states('init')->make(),
            'inspection'  => factory(Inspection::class)->states('init')->make(),
        ]);
        // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------
        $relations = array(
            'plan.setting', 'sale'
        );

        $mas_section    = Section::with($relations)->findOrFail($id); // get mas section data with its relationship
        $project        = $mas_section->project; // get project data
        // ---------------------------------------------------------------------

        // get sale contract data with its relationship
        // ---------------------------------------------------------------------
        $relations = array(
            'purchases', 'deposits', 'mediations',
            'residences', 'fee.prices'
        );

        $sale_contract = $mas_section->contract;
        if ($sale_contract) {
            $sale_contract->load($relations);
            $this->format_date($sale_contract);
        }else {
            $sale_contract = factory(Contract::class)->states('init', 'relations')->make([
                'mas_section_id' => $mas_section->id,
            ]);
            $sale_contract->load($relations);
        }
        // ---------------------------------------------------------------------

        // get mas lot building data where building type == 1
        // ---------------------------------------------------------------------
        $property  = Property::where('project_id', $project->id)->firstOrFail();
        $buildings = BuildingA::where('pj_property_id', $property->id ?? null)->with('mas_building')->get();

        $buildings = $buildings->map(function ($building, $key) { // get mas lot building data from pj lot building a relation
            if ($building->mas_building->building_type == 1)
                return $building->mas_building;
        });
        // ---------------------------------------------------------------------
        $mas_buildings = collect([]);
        foreach ($buildings as $key => $building) {
            if ($building) {
                $mas_buildings->push($building->load('building_floors'));
            }
        }
        $buildings = $mas_buildings;
        // ---------------------------------------------------------------------

        // get company bank accounts data
        // ---------------------------------------------------------------------
        $banks = [];
        $finance = Finance::where('project_id', $project->id)->with('accounts')->first();
        $accounts = $finance->accounts ?? [];
        // check if mas finance accounts not empty
        if (!empty($accounts)) {
            // get company bank where in finance accounts
            $bank_accounts = Account::whereIn('id', $accounts->pluck('account_main'))->with(['company', 'bank'])->get();
            foreach ($bank_accounts as $bank_account) {
                $company = $bank_account->company;
                $bank    = $bank_account->bank;
                $banks[] = [
                    'id'   => $bank_account->id,
                    'name' => "{$company->name_abbreviation} {$bank->name_branch_abbreviation} {$bank_account->account_number}"
                ];
            }
        }
        $banks = collect($banks);
        // ---------------------------------------------------------------------
        // get real estates data
        // ---------------------------------------------------------------------
        $in_house_n_real_estates = Company::where([
                                                ['kind_in_house', 1],
                                                ['kind_real_estate_agent', 1],
                                            ])->get();

        $bank_accounts = Account::whereIn('company_id', $in_house_n_real_estates->pluck('id'))
                                  ->with('bank', 'company')->get();
        // ---------------------------------------------------------------------
        // get kind_bank company
        // ---------------------------------------------------------------------
        $kind_banks         = Company::where('kind_bank', 1)->get();
        $kind_bank_accounts = Account::whereIn('company_id', $kind_banks->pluck('id'))
                                  ->with('bank', 'company')->get();
        // ---------------------------------------------------------------------

        // get inspection request data
        // ---------------------------------------------------------------------
        $query = Inspection::where( 'kind', 4 )
               ->where( 'active', true )
               ->where( 'project_id', $project->id )
               ->orderBy( 'created_at', 'desc' );
        $inspections        = $query->get();
        // ---------------------------------------------------------------------

        // compare sale purchase and inspection request data
        // ---------------------------------------------------------------------
        if (count($sale_contract->purchases) > 0 && count($inspections) > 0) {
            $purchase_ids   = $sale_contract->purchases->pluck('id');
            $inspection_ids = $inspections->pluck('context');

            if (count($purchase_ids) > count($inspection_ids)) {
                // get different sale purchase and request inspection id
                // -------------------------------------------------------------
                $different_ids = $purchase_ids->diff($inspection_ids);
                // -------------------------------------------------------------

                // create request inspection default data
                // -------------------------------------------------------------
                foreach ($different_ids as $key => $different_id) {
                    $default_data = factory(Inspection::class)->states('init')->make([
                        'id'      => null,
                        'context' => $different_id,
                    ]);
                    $inspections->push($default_data);
                }
                // -------------------------------------------------------------
            }
            // -----------------------------------------------------------------
        }
        // ---------------------------------------------------------------------

        // sale contract url
        // ---------------------------------------------------------------------
        $update = route('sale.contract.update', [
            'project'   => $project->id,
            'section'   => $id,
        ]);
        $remove_purchases = route('sale.contract.delete', [
            'project'     => $project->id,
            'section'     => $id,
            'type'        => 'purchases',
        ]);
        $remove_deposits = route('sale.contract.delete', [
            'project'    => $project->id,
            'section'    => $id,
            'type'       => 'deposits',
        ]);
        $remove_mediations  = route('sale.contract.delete', [
            'project'   => $project->id,
            'section'   => $id,
            'type'      => 'mediations',
        ]);
        $remove_prices  = route('sale.contract.delete', [
            'project'   => $project->id,
            'section'   => $id,
            'type'      => 'prices',
        ]);
        $inspection_request = route('project.inspection.request', [
            'project' => $project->id,
            'type'    => 4,
        ]);

        $url = collect([
            'update'               => $update,
            'remove_purchases'     => $remove_purchases,
            'remove_deposits'      => $remove_deposits,
            'remove_mediations'    => $remove_mediations,
            'remove_prices'        => $remove_prices,
            'inspection_request'   => $inspection_request,
        ]);
        // ---------------------------------------------------------------------

        // assign data
        // ---------------------------------------------------------------------
        $data                     = new \stdClass;
        $data->master_values      = $master_values;
        $data->master_regions     = $master_regions;
        $data->initial            = $initial;
        $data->editable           = $editable;
        $data->url                = $url;
        $data->page_title         = "販売契約::{$project->title}({$mas_section->section_number})";
        $data->users              = $users;
        $data->role               = $role;
        $data->companies          = $companies;
        $data->banks              = $banks;
        $data->bank_accounts      = $bank_accounts;
        $data->kind_bank_accounts = $kind_bank_accounts;
        $data->project            = $project;
        $data->mas_section        = $mas_section;
        $data->buildings          = $buildings;
        $data->sale_contract      = $sale_contract;
        $data->inspections        = $inspections->keyBy('context');
        // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------
        return $data;
        // ---------------------------------------------------------------------
    }

    public function format_date($sale_contract)
    {
        $format = 'Y/m/d';

        // sale contract
        // ---------------------------------------------------------------------
        $sale_contract->purchase_date = $sale_contract->purchase_date ?
            Carbon::parse($sale_contract->purchase_date)->format($format) : null;
        $sale_contract->contract_date = $sale_contract->contract_date ?
            Carbon::parse($sale_contract->contract_date)->format($format) : null;
        $sale_contract->payment_date  = $sale_contract->payment_date ?
            Carbon::parse($sale_contract->payment_date)->format($format) : null;
        $sale_contract->delivery_date = $sale_contract->delivery_date ?
            Carbon::parse($sale_contract->delivery_date)->format($format) : null;
        $sale_contract->real_estate_tax_income_date = $sale_contract->real_estate_tax_income_date ?
            Carbon::parse($sale_contract->real_estate_tax_income_date)->format($format) : null;
        // ---------------------------------------------------------------------

        // sale purchase
        // ---------------------------------------------------------------------
        foreach ($sale_contract->purchases as $key => $purchase) {
            $purchase->contract_date_request    = $purchase->contract_date_request ?
                Carbon::parse($purchase->contract_date_request)->format($format) : null;
            $purchase->payment_date_request     = $purchase->payment_date_request ?
                Carbon::parse($purchase->payment_date_request)->format($format) : null;
        }
        // ---------------------------------------------------------------------

        // sale contract deposit
        // ---------------------------------------------------------------------
        foreach ($sale_contract->deposits as $key => $deposit) {
            $deposit->date    = $deposit->date ?
                Carbon::parse($deposit->date)->format($format) : null;
        }
        // ---------------------------------------------------------------------

        // sale product residence
        // ---------------------------------------------------------------------
        foreach ($sale_contract->residences as $key => $residence) {
            $residence->receipt_date    = $residence->receipt_date ?
                Carbon::parse($residence->receipt_date)->format($format) : null;
        }
        // ---------------------------------------------------------------------

        // sale mediation
        // ---------------------------------------------------------------------
        foreach ($sale_contract->mediations as $key => $mediation) {
            $mediation->date    = $mediation->date ?
                Carbon::parse($mediation->date)->format($format) : null;
        }
        // ---------------------------------------------------------------------

        // sale fee price
        // ---------------------------------------------------------------------
        if ($sale_contract->fee) {
            foreach ($sale_contract->fee->prices as $key => $price) {
                $price->date    = $price->date ?
                    Carbon::parse($price->date)->format($format) : null;
            }
        }
        // ---------------------------------------------------------------------
    }
}
