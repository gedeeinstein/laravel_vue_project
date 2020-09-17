<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Master;
// --------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
// --------------------------------------------------------------------------
use App\Models\Company;
use App\Models\CompanyBankAccount;
use App\Models\CompanyBorrower;
use App\Models\User;
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\MasFinance;
use App\Models\MasFinancePurchaseContractor;
use App\Models\MasFinancePurchaseAccountMain;
use App\Models\MasFinanceUnit;
use App\Models\MasFinanceUnitMoney;
use App\Models\MasFinanceRepayment;
use App\Models\MasFinanceReturnBank;
use App\Models\MasFinanceExpense;

class MasterFinanceController extends Controller {

    private $project;

    // ----------------------------------------------------------------------
    // Construct scoped value
    // ----------------------------------------------------------------------
    public function __construct(){
        $this->kana_index = config( 'const.KANA_INDEX' );
        $this->taxes = config( 'const.JAPANESE_TAX' );
    }

    // ----------------------------------------------------------------------
    // Project Finance Index Page
    // ----------------------------------------------------------------------
    public function index( $project_id ){
        $data = new \stdClass;
        // ------------------------------------------------------------------
        $data->data = $this->getFinanceData($project_id);
        $data->master = $this->getMasterData();
        $data->project = $this->project;
        // ------------------------------------------------------------------
        $data->page_title = "融資・入出金::{$data->project->title}";
        $data->update_url = route('master.finance.update', $data->project->id);
        $data->delete_url = route('master.finance.delete', $data->project->id);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // return data finance to view
        // ------------------------------------------------------------------
        return view('backend.master.finance.finance', (array)$data);
    }

    // ----------------------------------------------------------------------
    // Get Master Finance Related Data
    // ----------------------------------------------------------------------
    public function getFinanceData( $project_id ){
        $data = new \stdClass;
        // ------------------------------------------------------------------
        $data->kana_index = $this->kana_index;
        $data->taxes = $this->taxes;
        // ------------------------------------------------------------------
        // get project with all master finance relations
        // ------------------------------------------------------------------
        $project = Project::with([
            'purchaseSale',
            'purchase.targets.purchase_target_contractors.contractor',
            'finance.contractors.purchaser',
            'finance.accounts',
            'finance.units.moneys',
            'finance.units.repaymentSales',
            'finance.units.repaymentOthers',
            'finance.returnBank',
            'finance.expenses',
            'setting.plans.sections'
        ])->findOrFail($project_id);
        $this->project = $project;
        $data->project = $project;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // get contractors data
        // ------------------------------------------------------------------
        $contractors = [];
        $targets = $project->purchase->targets ?? [];
        foreach ($targets as $target) {
            foreach ($target->purchase_target_contractors as $contractor) {
                $contractors[] = $contractor->contractor;
            }
        }
        $data->contractors = $contractors;
        // ------------------------------------------------------------------
        // get master setting sections
        // ------------------------------------------------------------------
        $section_numbers = [];
        $plans = $project->setting->plans ?? [];
        foreach ($plans as $plan) {
            foreach ($plan->sections as $section) {
                $section_numbers[] = $section->section_number;
            }
        }
        $data->section_numbers = $section_numbers;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // get expense data
        // ------------------------------------------------------------------
        $sheet = $project->withBudgetSheet()->with('stock.finances.stockCost.rows')->first();
        $finance_expense = $project->finance->expenses ?? collect();
        $finance = $project->finance;
        $stock_finance = $sheet->stock->finances ?? null;
        $expenses = [];
        # get payee data
        $master_data = $this->getMasterData();
        $payees = collect($master_data->payees);
        # get_finance()
        # Use closure helper to get finance data
        $get_finance = function($category, $name, $budget) use($finance, $finance_expense, $payees) {
            // filter expense data
            $filtered = $finance_expense->where('category_index', $category)->where('display_name', $name);
            // if exist data
            if ($filtered->isNotEmpty()) {
                $filtered->map(function ($row) use($payees, $budget) {
                    $row['budget'] = $budget;
                    if (isset($row['payee'])) {
                        $row['payee'] = $payees->where('id', $row['payee'])->where('type', $row['payee_type'])->first();
                    }
                });
                return $filtered->values();
            }
            return collect([
                [
                    'id' => '', 'mas_finance_id' => $finance->id ?? '',
                    'budget' => $budget,
                    'decided' => '', 'payperiod' => '', 'payee' => '', 'note' => '',
                    'paid' => '', 'date' => '', 'bank' => '', 'taxfree' => '', 'status' => 0,
                    'category_index'   => $category,
                    'display_name'  => $name
                ]
            ]);
        };
        # interest rate burden
        $interest = $stock_finance->total_interest_rate ?? 0;
        $expenses['data'][] = $get_finance('ア', '金利負担', $interest);
        # banking fee
        $fee = $stock_finance->banking_fee ?? 0;
        $expenses['data'][] = $get_finance('イ', '銀行手数料', $fee);
        # stamp
        $stamp = $stock_finance->stamp ?? 0;
        $expenses['data'][] = $get_finance('ウ', '印紙(手形)', $stamp);
        # get other data
        $finance_others = $stock_finance->stockCost->rows ?? [];
        $expenses_index = count($expenses['data']);
        foreach ($finance_others as $row) {
            $expenses_index++;
            $kana = $this->kana_index[$expenses_index];
            $item = $get_finance($kana, $row->name, $row->value);
            $expenses['data'][] = $item;
            $item->transform( function( $entry ) use( $row ){
                $entry['additional_id'] = $row->id;
                $entry['other'] = true;
                return $entry;
            });
        }
        $data->expenses = $expenses;
        return $data;
    }

    // ----------------------------------------------------------------------
    // Get Master Finance Related Data
    // ----------------------------------------------------------------------
    public function getMasterData( $filter = [] ){
        $data = new \stdClass;
        $data->banks = [];
        $data->borrowers = [];

        // ------------------------------------------------------------------
        // Payees
        // ------------------------------------------------------------------
        // Select G122-2 (company name kana)
        $payees = [];
        $companies = Company::select('id', 'name_kana', 'kind_bank')->with('banks')->orderBy('id', 'asc')->get();
        $individuals = User::select('id', 'first_name_kana', 'last_name_kana')->where('user_role_id', '!=', 6)->whereNull('company_id')->get();
        foreach ($companies as $company) {
            // If company has banks G122-10
            if ($company->banks()->exists()) {
                // G122-2 + G126-1
                foreach ($company->banks as $bank) {
                    $payees[] = [
                        'id'   => $bank->id,
                        'type' => 'bank',
                        'name' => $company->name_kana.' '.$bank->name_branch
                    ];
                }
            }else{
                $payees[] = [
                    'id'   => $company->id,
                    'type' => 'company',
                    'name' => $company->name_kana
                ];
            }
        }
        // ------------------------------------------------------------------
        // Add individuals
        foreach ($individuals as $individual) {
            $payees[] = [
                'id'   => $individual->id,
                'type' => 'individual',
                'name' => $individual->first_name_kana.' '.$individual->last_name_kana
            ];
        }
        $data->payees = $payees;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Bank Account & Borrower
        // ------------------------------------------------------------------
        // get compnay data with bank account
        // related to purchase sale company organizer
        $company_id_organizer = $this->project->purchaseSale->company_id_organizer ?? null;
        $company = Company::where('id', $company_id_organizer)->first();
        // check if empty data
        if (!isset($company->id)) {
            return $data;
        }
        $bank_accounts = CompanyBankAccount::where('company_id', $company->id)->with(['company', 'bank'])->get();
        $company_borrowers = CompanyBorrower::where('company_id', $company->id)->with(['company', 'bank'])->get();
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
        $data->banks = $banks;
        // ------------------------------------------------------------------
        $borrowers = [];
        foreach ($company_borrowers as $borrower) {
            $borrowers[] = [
                'id'   => $borrower->id,
                'bank_name' => "{$borrower->bank->company->name} - {$borrower->bank->name_branch}"
            ];
        }
        // ------------------------------------------------------------------
        $data->borrowers = $borrowers;
        // ------------------------------------------------------------------

        return $data;
    }

    // ----------------------------------------------------------------------
    // Master Finance Update Handler
    // ----------------------------------------------------------------------
    public function update($project_id, Request $request){
        try {
            // --------------------------------------------------------------
            // Master Finnace
            // --------------------------------------------------------------
            $data_finance = [
                'id' => $request->finance_id,
                'project_id' => $project_id
            ];
            $finance = MasFinance::updateOrCreate( $data_finance, $data_finance );

            // --------------------------------------------------------------
            // Master Finnace Contractors
            // --------------------------------------------------------------
            foreach ($request->contractors as $contractor) {
                if (!isset($contractor['disabled'])) {
                    unset($contractor['purchaser']);
                    $contractor['mas_finance_id'] = $finance->id;
                    MasFinancePurchaseContractor::updateOrCreate([
                        'id'             => $contractor['id'],
                        'mas_finance_id' => $finance->id
                    ], $contractor );
                }
            }

            // --------------------------------------------------------------
            // Master Finnace Contractors
            // --------------------------------------------------------------
            foreach ($request->accounts as $account) {
                $account['mas_finance_id'] = $finance->id;
                // update data
                // ----------------------------------------------------------
                if (empty($account['deleted'])) {
                    MasFinancePurchaseAccountMain::updateOrCreate([
                        'id'             => $account['id'],
                        'mas_finance_id' => $finance->id
                    ], $account );
                }
                // delete data
                // ----------------------------------------------------------
                else {
                    $deleted = MasFinancePurchaseAccountMain::find($account['id']);
                    $deleted->delete();
                }
            }

            // --------------------------------------------------------------
            // Master Finnace Loans
            // --------------------------------------------------------------
            foreach ($request->loans as $key => $loan) {
                // ----------------------------------------------------------
                $moneys = $loan['moneys'];
                $repayment_sales = $loan['repayment_sales'];
                $repayment_others = $loan['repayment_others'];
                unset($loan['moneys']);
                unset($loan['repayment']);
                unset($loan['repayment_sales']);
                unset($loan['repayment_others']);
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                //  Units / Loans
                // ----------------------------------------------------------
                $loan['mas_finance_id'] = $finance->id;
                $unit = MasFinanceUnit::updateOrCreate([
                    'id'             => $loan['id'],
                    'mas_finance_id' => $finance->id
                ], $loan );

                // ----------------------------------------------------------
                // Units Moneys
                // ----------------------------------------------------------
                foreach ($moneys as $money) {
                    $money['mas_finance_unit_id'] = $unit->id;
                    // update data
                    // ------------------------------------------------------
                    if (empty($money['deleted'])) {
                        MasFinanceUnitMoney::updateOrCreate([
                            'id'                  => $money['id'],
                            'mas_finance_unit_id' => $unit->id
                        ], $money );
                    }
                    // delete data
                    // ------------------------------------------------------
                    else {
                        $deleted = MasFinanceUnitMoney::find($money['id']);
                        $deleted->delete();
                    }
                }

                // ----------------------------------------------------------
                // Units Repayments Sales Dividen
                // ----------------------------------------------------------
                foreach ($repayment_sales as $sales) {
                    unset($sales['disabled']);
                    $sales['mas_finance_unit_id'] = $unit->id;
                    MasFinanceRepayment::updateOrCreate([
                        'id'                  => $sales['id'],
                        'mas_finance_unit_id' => $unit->id
                    ], $sales );
                }

                // ----------------------------------------------------------
                // Units Repayments Other
                // ----------------------------------------------------------
                foreach ($repayment_others as $other) {
                    $other['mas_finance_unit_id'] = $unit->id;
                    // update data
                    // ------------------------------------------------------
                    if (empty($other['deleted'])) {
                        MasFinanceRepayment::updateOrCreate([
                            'id'                  => $other['id'],
                            'mas_finance_unit_id' => $unit->id
                        ], $other );
                    }
                    // delete data
                    // ------------------------------------------------------
                    else {
                        $deleted = MasFinanceRepayment::find($other['id']);
                        $deleted->delete();
                    }
                }
            }

            // --------------------------------------------------------------
            // Master Finnace Return Bank
            // --------------------------------------------------------------
            $data_bank = $request->return_bank;
            $data_bank['mas_finance_id'] = $finance->id;
            MasFinanceReturnBank::updateOrCreate([
                'id'             => $data_bank['id'],
                'mas_finance_id' => $finance->id
            ], $data_bank );

            // --------------------------------------------------------------
            // Master Finnace Expense
            // --------------------------------------------------------------
            $expense = $request->expenses;
            foreach ($expense['data'] as $entry) {
                foreach ($entry as $data) {
                    if (empty($data['deleted'])) {
                        unset($data['budget']);
                        unset($data['other']);
                        $data['mas_finance_id'] = $finance->id;
                        // cast payperiod to date format and set default day 01
                        $data['payperiod'] = empty($data['payperiod']) ? $data['payperiod'] : Carbon::createFromFormat('y/m', $data['payperiod'])->setDay(01);
                        // extract payee data
                        $payee = collect($data['payee']);
                        $data['payee'] = $payee->get('id');
                        $data['payee_type'] = $payee->get('type');
                        // update or create
                        MasFinanceExpense::updateOrCreate([
                            'id'             => $data['id'],
                            'mas_finance_id' => $finance->id
                        ], $data );
                    }
                    else {
                        $deleted = MasFinanceExpense::find($data['id']);
                        $deleted->delete();
                    }
                }
            }

            // --------------------------------------------------------------
            // return success message with response data
            // --------------------------------------------------------------
            $responses = $this->getFinanceData($project_id);;
            return response()->json([
                'status'  => 'success',
                'message' => __('label.success_update_message'),
                'data'    => $responses
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
    // Master Finance Delete Loan Handler
    // ----------------------------------------------------------------------
    public function delete($project_id, Request $request){
        try {
            $loan = MasFinanceUnit::findOrFail($request->id);
            $loan->moneys()->delete();
            $loan->repayments()->delete();
            $loan->delete();

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

}
