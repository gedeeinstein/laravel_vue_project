<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Project;
// --------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
// --------------------------------------------------------------------------
use App\Models\MasterValue;
use App\Models\MasterRegion;
use App\Models\Company;
use App\Models\CompanyBankAccount;
use App\Models\User;
use App\Models\Project;
// --------------------------------------------------------------------------
use App\Models\PjExpense;
use App\Models\PjExpenseRow;
// --------------------------------------------------------------------------
use App\Models\MasFinance;
use App\Models\MasSection;

class ProjectExpenseController extends Controller {

    private $project, $expense, $expense_rows, $kana_index;

    // ----------------------------------------------------------------------
    // Construct scoped value
    // ----------------------------------------------------------------------
    public function __construct(){
        $this->kana_index = config( 'const.KANA_INDEX' );
        $this->taxes = config( 'const.JAPANESE_TAX' );
    }

    // ----------------------------------------------------------------------
    // Project Expense Index Page
    // ----------------------------------------------------------------------
    public function index( $project_id ){
        // get related data from database, set global data
        // ------------------------------------------------------------------
        $this->project = Project::findOrFail($project_id);
        $this->expense = PjExpense::where('project_id', $project_id)->first();
        // ------------------------------------------------------------------
        $masters = $this->get_master_data();
        $sections = $this->get_sections_data();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // assign data
        // ------------------------------------------------------------------
        $data = new \stdClass;
        $data->page_title = "支出の部::{$this->project->title}";
        $data->update_url = route('project.expense.update', $project_id);
        $data->delete_url = route('project.expense.delete', $project_id);
        // ------------------------------------------------------------------
        $data->taxes = $this->taxes;
        $data->project = $this->project;
        $data->expense = $this->expense;
        $data->master = $masters;
        $data->sections = $sections;
        // ------------------------------------------------------------------

        // return data expense to view
        // ------------------------------------------------------------------
        // dd( $data->sections['c'] );
        return view('backend.project.expense.expense', (array)$data);
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Provide calculated data to be used on other modules
    // ----------------------------------------------------------------------
    public function import( $projectID ){
        // ------------------------------------------------------------------
        $this->project = Project::findOrFail( $projectID );
        $this->expense = PjExpense::where( 'project_id', $projectID )->first();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // assign data
        // ------------------------------------------------------------------
        $data = new \stdClass;
        $data->taxes = $this->taxes;
        $data->project = $this->project;
        $data->expense = $this->expense;
        $data->master = $this->get_master_data();
        $data->sections = $this->get_sections_data();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return $data;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Get Master Data or Return Single Value Data
    // ----------------------------------------------------------------------
    public function get_master_data($type = 'all', $value = ''){
        $project = $this->project;
        // ------------------------------------------------------------------
        // Masters Data Payees
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
        // Add individuals
        foreach ($individuals as $individual) {
            $payees[] = [
                'id'   => $individual->id,
                'type' => 'individual',
                'name' => $individual->first_name_kana.' '.$individual->last_name_kana
            ];
        }
        $payees = collect($payees);
        //  get real estates data
        $real_estates = Company::where('kind_real_estate_agent', '1')->with('banks')->get();

        // ------------------------------------------------------------------
        // Masters Data Banks
        // ------------------------------------------------------------------
        $banks = [];
        $finance = MasFinance::where('project_id', $project->id)->with('accounts')->first();
        $accounts = $finance->accounts ?? [];
        // check if mas finance accounts not empty
        if (!empty($accounts)) {
            // get company bank where in finance accounts
            $bank_accounts = CompanyBankAccount::whereIn('id', $accounts->pluck('account_main'))->with(['company', 'bank'])->get();
            foreach ($bank_accounts as $bank_account) {
                $company = $bank_account->company;
                $bank = $bank_account->bank;
                $banks[] = [
                    'id'   => $bank_account->id,
                    'name' => "{$company->name_abbreviation} {$bank->name_branch_abbreviation} {$bank_account->account_number}"
                ];
            }
        }
        $banks = collect($banks);
        // get all bank not limited with mas finance accounts
        $all_bank = CompanyBankAccount::with('bank.company')->get();

        switch ($type) {
            case 'all':
                return [
                    'payee' => $payees, 'real_estates' => $real_estates,
                    'bank'  => $banks, 'all_bank' => $all_bank
                ];
            case 'payee':
                return $payees->where('id', $value['id'])->where('type', $value['type'])->first();
            case 'bank':
                return $banks->where('id', $value)->pluck('name')->first();
            default:
                break;
        }
    }

    // ----------------------------------------------------------------------
    // Get Formated Sections Data From Database
    // ----------------------------------------------------------------------
    public function get_sections_data(){
        $sections = collect();
        $project = $this->project;
        $expense = $this->expense;
        $kana_index = $this->kana_index;

        // ------------------------------------------------------------------
        // Data share to all sections
        // ------------------------------------------------------------------
        // check if expense is exist
        $this->expense_rows = isset($expense->id) ? PjExpenseRow::where('pj_expense_id', $expense->id)->get() : collect();

        // get data from pj_sheet where is_reflecting_in_budget = 1
        $sheet = $project->withBudgetSheet()->with([
            'stock.procurements',
            'stock.registers.stockCost.rows',
            'stock.taxes.stockCost.rows',
            'stock.constructions.stockCost.rows',
            'stock.surveys.stockCost.rows',
            'stock.others.stockCost.rows',
            'stock.finances'
        ])->first();
        $procurement = $sheet->stock->procurements ?? null;

        // get data from purchase
        $purchase = $project->purchase()->with(
            'targets.contract.deposits',
            'targets.contract.mediations',
            'targets.purchase_target_contractors.contractor'
        )->first();
        $purchase_targets = $purchase->targets ?? [];

        //  Get Data From Mas Finances
        $finance = MasFinance::where('project_id', $project->id)->with('expenses')->first();
        $finance_expense = $finance->expenses ?? collect();

        // Get Data From Master Sections
        $master_sections = MasSection::whereProject($project->id)->with([
            'saleContract.mediations',
            'saleContract.fee.prices',
        ])->get();

        // ------------------------------------------------------------------
        // Section A
        // ------------------------------------------------------------------
        $data_a = [];
        $data_a['budget'][] = $procurement->price ?? 0;
        // looop purchase targets to merge expense data
        foreach ($purchase_targets as $key => $target) {
            $decided_a = $target->contract->contract_price_total ?? 0;
            // loop contractor data
            foreach ($target->purchase_target_contractors as $target_contractor) {
                $data_a['payee'][] = $target_contractor->contractor->name ?? '';
            }
            // loop deposits data
            $deposits = $target->contract->deposits ?? [];
            foreach ($deposits as $deposit) {
                $data_a['note'][] = $deposit->note ?? '';
                $data_a['paid'][] = $deposit->price ?? 0;
                $data_a['date'][] = format_date($deposit->date ?? null);
                $data_a['bank'][] = $deposit->account ?? '';
                $data_a['status'][] = $deposit->status ?? '';
            }
            // added contract delivery
            $contract = $target->contract;
            $data_a['note'][] = $contract->contract_delivery_note ?? '';
            $data_a['paid'][] = $contract->contract_delivery_money ?? 0;
            $data_a['date'][] = format_date($contract->contract_delivery_date ?? null);
            $data_a['bank'][] = $contract->contract_delivery_bank ?? '';
            $data_a['status'][] = $contract->contract_delivery_status ?? '';
        }
        // set decided data
        $data_a['decided'][] = $decided_a ?? 0;
        // set default biggest data
        $biggest = $data_a['budget'];
        // loop data to find biggest length of data
        foreach ($data_a as $key => $data) {
            if (sizeof($data) > sizeof($biggest)) {
                $biggest = $data;
            }
        }
        // format data to display on view
        foreach ($biggest as $i => $value) {
            $formated[] = [
                'budget'  => $data_a['budget'][$i] ?? '',
                'decided' => $data_a['decided'][$i] ?? '',
                'payee'   => $data_a['payee'][$i] ?? '',
                'note'    => $data_a['note'][$i] ?? '',
                'paid'    => $data_a['paid'][$i] ?? '',
                'date'    => $data_a['date'][$i] ?? '',
                'bank'    => $data_a['bank'][$i] ?? '',
                'status'  => $data_a['status'][$i] ?? '',
            ];
        }
        $section_a = new \stdClass;
        $section_a->procurement = $procurement;
        $section_a->data[] =  $formated;
        $sections->put('a', $section_a);

        // ------------------------------------------------------------------
        // Section B
        // ------------------------------------------------------------------
        $data_b = [];
        // loop purchase targets to add mediation data
        foreach ($purchase_targets as $key => $target) {
            $mediations = $target->contract->mediations ?? [];
            foreach ($mediations as $mediation) {
                $data_b[] = [
                    'budget'  => $procurement->brokerage_fee ?? 0,
                    'decided' => $mediation->reward ?? 0,
                    'payee'   => $mediation->trader_company_id ?? '',
                    'paid'    => $mediation->reward ?? 0,
                    'date'    => format_date($mediation->date ?? null),
                    'bank'    => $mediation->bank ?? '',
                    'status'  => $mediation->status ?? '',
                ];
            }
        }
        // added basic data if empty
        if (empty($data_b)) {
            $data_b[] = [
                'budget'  => 0, 'decided' => 0,
                'payee'   => '', 'paid'    => '',
                'date'    => '', 'bank'    => '',
                'status'  => '',
            ];
        }
        $section_b = new \stdClass;
        $section_b->procurement = $procurement;
        $section_b->data[] = $data_b;
        $sections->put('b', $section_b);

        // ------------------------------------------------------------------
        // Section C
        // ------------------------------------------------------------------
        $section_c = [];
        $register = $sheet->stock->registers ?? null;
        # registration tax
        $section_c['data']['row1_0'] = $this->get_basic_data(
            'screen_c', $kana_index[1] , 'registration_tax', [ 'name' => '司法書士登記', 'taxfree' => 1, 'const_tax' => true ]
        );
        # registration reward
        $section_c['data']['row1_1'] = $this->get_basic_data(
            'screen_c', $kana_index[1] , 'reward', [ 'name' => '司法書士登記' ]
        );
        # fixed asset tax
        $section_c['data']['row2'] = $this->get_basic_data(
            'screen_c', $kana_index[2], '', [ 'name' => '固都税日割分', 'taxfree' => 1, 'const_tax' => true, 'budget' => $register->fixed_assets_tax ?? 0 ]
        );
        # registration loss
        $section_c['data']['row3'] = $this->get_basic_data(
            'screen_c', $kana_index[3], '', [ 'name' => '滅失登記', 'budget' => $register->loss ?? 0 ]
        );
        # check if exist other data
        $register_rows = $register->stockCost->rows ?? [];
        # other index
        $section_c_index = count($section_c['data'])-1;
        foreach ($register_rows as $row) {
            $section_c_index++;
            $kana = $this->kana_index[$section_c_index];
            $data = $this->get_basic_data(
                'screen_c', $kana , '', ['name' => $row->name, 'budget' => $row->value]
            );
            $section_c['other'][] = $data;
            $data->transform( function( $entry ) use( $row ){
                $entry['additional_id'] = $row->id;
                return $entry;
            });
        }
        # calculate total register budget
        $register_transfer = $register->transfer_of_ownership ?? 0;
        $register_mortgage = $register->mortgage_setting ?? 0;
        $section_c['register_budget'] = intval($register_transfer)+intval($register_mortgage);

        $sections->put('c', $section_c);

        // ------------------------------------------------------------------
        // Section D
        // ------------------------------------------------------------------
        # pj_stock_finances.total_interest_rate
        $stock_finance = $sheet->stock->finances ?? null;
        $section_d = [];
        # Use closure helper to get finance data
        $get_finance = function($category, $name) use($expense, $finance_expense, $stock_finance) {
            $filtered = $finance_expense->where('category_index', $category)->where('display_name', $name);
            if ($filtered->isNotEmpty()) {
                $filtered->map(function ($row, $index) use($stock_finance) {
                    $row['budget'] = $stock_finance->total_interest_rate ?? 0;
                });
                return $filtered->values();
            }
            return [
                [
                    'id' => '', 'pj_expense_id' => $expense->id ?? '',
                    'budget' => $stock_finance->total_interest_rate ?? 0,'decided' => '',
                    'payperiod' => '', 'payee' => '', 'note' => '', 'paid' => '',
                    'date' => '', 'bank' => '', 'taxfree' => '', 'status' => 0,
                    'category_index'   => $category,
                    'display_name'  => $name
                ]
            ];
        };
        # interest rate burden
        $section_d['data'][] = $get_finance('ア', '金利負担');
        # banking fee
        $section_d['data'][] = $get_finance('イ', '銀行手数料');
        # stamp
        $section_d['data'][] = $get_finance('ウ', '印紙(手形)');
        # get other data
        $finance_others = $finance_expense->whereNotIn('category_index', ['ア','イ','ウ'])->groupBy('category_index');
        foreach ($finance_others as $other) {
            $other->map(function ($row, $index) use($stock_finance) {
                $row['other'] = true;
                $row['budget'] = $stock_finance->total_interest_rate ?? 0;
            });
            $section_d['data'][] = $other;
        }
        $sections->put('d', $section_d);

        // ------------------------------------------------------------------
        // Section E
        // ------------------------------------------------------------------
        $section_e = [];
        $taxes = $sheet->stock->taxes ?? null;
        # fixed taxes year
        $section_e['data'][] = $this->get_basic_data(
            'screen_e', $kana_index[1], '', [ 'name' => '翌年固都税' ,'taxfree' => 1, 'const_tax' => true, 'budget' => $taxes->the_following_year_the_city_tax ?? 0 ]
        );
        # check if exist other data
        $taxes_rows = $taxes->stockCost->rows ?? [];
        # other index
        $section_e_index = count($section_e['data']);
        foreach ($taxes_rows as $row) {
            $section_e_index++;
            $kana = $this->kana_index[$section_e_index];
            $data = $this->get_basic_data(
                'screen_e', $kana , '', ['name' => $row->name, 'budget' => $row->value, 'other' => true]
            );
            $section_e['data'][] = $data;
            $data->transform( function( $entry ) use( $row ){
                $entry['additional_id'] = $row->id;
                return $entry;
            });
        }
        $sections->put('e', $section_e);

        // ------------------------------------------------------------------
        // Section F
        // ------------------------------------------------------------------
        $section_f = [];
        $constructions = $sheet->stock->constructions ?? null;
        # building demolition
        $section_f['data'][] = $this->get_basic_data(
            'screen_f', $kana_index[1], '', [ 'name' => '建物解体工事' , 'budget' => $constructions->building_demolition ?? 0 ]
        );
        # retaining wall demolition
        $section_f['data'][] = $this->get_basic_data(
            'screen_f', $kana_index[2], '', [ 'name' => '擁壁解体工事' , 'budget' => $constructions->retaining_wall_demolition ?? 0 ]
        );
        # transfer electric pole
        $section_f['data'][] = $this->get_basic_data(
            'screen_f', $kana_index[3], '', [ 'name' => '電柱移設・撤去' , 'budget' => $constructions->transfer_electric_pole ?? 0 ]
        );
        # waterwork construction
        $section_f['data'][] = $this->get_basic_data(
            'screen_f', $kana_index[4], '', [ 'name' => '水道・下水工事' , 'budget' => $constructions->waterwork_construction ?? 0 ]
        );
        # filling work
        $section_f['data'][] = $this->get_basic_data(
            'screen_f', $kana_index[5], '', [ 'name' => '盛り土工事' , 'budget' => $constructions->fill_work ?? 0 ]
        );
        # retaining wall construction
        $section_f['data'][] = $this->get_basic_data(
            'screen_f', $kana_index[6], '', [ 'name' => '擁壁工事' , 'budget' => $constructions->retaining_wall_construction ?? 0 ]
        );
        # road construction
        $section_f['data'][] = $this->get_basic_data(
            'screen_f', $kana_index[7], '', [ 'name' => '道路工事' , 'budget' => $constructions->road_construction ?? 0 ]
        );
        # side groove construction
        $section_f['data'][] = $this->get_basic_data(
            'screen_f', $kana_index[8], '', [ 'name' => '側溝工事' , 'budget' => $constructions->side_groove_construction ?? 0 ]
        );
        # construction work set
        $section_f['data'][] = $this->get_basic_data(
            'screen_f', $kana_index[9], '', [ 'name' => '造成工事' , 'budget' => $constructions->construction_work_set ?? 0 ]
        );
        # location designation application fee
        $section_f['data'][] = $this->get_basic_data(
            'screen_f', $kana_index[10], '', [ 'name' => '位置指定申請費' , 'budget' => $constructions->location_designation_application_fee ?? 0 ]
        );
        # development commissions fee
        $section_f['data'][] = $this->get_basic_data(
            'screen_f', $kana_index[11], '', [ 'name' => '開発委託費' , 'budget' => $constructions->development_commissions_fee ?? 0 ]
        );
        # cultural property research fee
        $section_f['data'][] = $this->get_basic_data(
            'screen_f', $kana_index[12], '', [ 'name' => '文化財調査費' , 'budget' => $constructions->cultural_property_research_fee ?? 0 ]
        );
        # check if exist other data
        $constructions_rows = $constructions->stockCost->rows ?? [];
        # other index
        $section_f_index = count($section_f['data']);
        foreach ($constructions_rows as $row) {
            $section_f_index++;
            $kana = $this->kana_index[$section_f_index];
            $data = $this->get_basic_data(
                'screen_f', $kana , '', ['name' => $row->name, 'budget' => $row->value, 'other' => true]
            );
            $section_f['data'][] = $data;
            $data->transform( function( $entry ) use( $row ){
                $entry['additional_id'] = $row->id;
                return $entry;
            });
        }
        $sections->put('f', $section_f);

        // ------------------------------------------------------------------
        // Section G
        // ------------------------------------------------------------------
        $section_g = [];
        $surveys = $sheet->stock->surveys ?? null;
        # fixed survey
        $section_g['data'][] = $this->get_basic_data(
            'screen_g', $kana_index[1], '', [ 'name' => '確定測量', 'budget' => $surveys->fixed_survey ?? 0 ]
        );
        # divisional registration
        $section_g['data'][] = $this->get_basic_data(
            'screen_g', $kana_index[2], '', [ 'name' => '分筆登記', 'budget' => $surveys->divisional_registration ?? 0 ]
        );
        # boundary pile restoration
        $section_g['data'][] = $this->get_basic_data(
            'screen_g', $kana_index[3], '', [ 'name' => '境界杭復元', 'budget' => $surveys->boundary_pile_restoration ?? 0 ]
        );
        # check if exist other data
        $surveys_rows = $surveys->stockCost->rows ?? [];
        # other index
        $section_g_index = count($section_g['data']);
        foreach ($surveys_rows as $row) {
            $section_g_index++;
            $kana = $this->kana_index[$section_g_index];
            $data = $this->get_basic_data(
                'screen_g', $kana , '', ['name' => $row->name, 'budget' => $row->value, 'other' => true]
            );
            $section_g['data'][] = $data;
            $data->transform( function( $entry ) use( $row ){
                $entry['additional_id'] = $row->id;
                return $entry;
            });
        }
        $sections->put('g', $section_g);

        // ------------------------------------------------------------------
        // Section H
        // ------------------------------------------------------------------
        $section_h = [];
        $others = $sheet->stock->others ?? null;
        # referral fee
        $section_h['data'][] = $this->get_basic_data(
            'screen_h', $kana_index[1], '', [ 'name' => '紹介料', 'budget' => $others->referral_fee ?? 0 ]
        );
        # eviction fee
        $section_h['data'][] = $this->get_basic_data(
            'screen_h', $kana_index[2], '', [ 'name' => '立ち退き料', 'budget' => $others->eviction_fee ?? 0 ]
        );
        # water supply subscription
        $section_h['data'][] = $this->get_basic_data(
            'screen_h', $kana_index[3], '', [ 'name' => '前払水道加入金', 'budget' => $others->water_supply_subscription ?? 0 ]
        );
        # check if exist other data
        $others_rows = $others->stockCost->rows ?? [];
        # other index
        $section_h_index = count($section_h['data']);
        foreach ($others_rows as $row) {
            $section_h_index++;
            $kana = $this->kana_index[$section_h_index];
            $data = $this->get_basic_data(
                'screen_h', $kana , '', ['name' => $row->name, 'budget' => $row->value, 'other' => true]
            );
            $section_h['data'][] = $data;
            $data->transform( function( $entry ) use( $row ){
                $entry['additional_id'] = $row->id;
                return $entry;
            });
        }
        $sections->put('h', $section_h);

        // ------------------------------------------------------------------
        // Section i
        // ------------------------------------------------------------------
        $section_i = [];
        // Loop Master Sections Get Sale Data
        foreach ($master_sections as $section) {
            // loop sale mediation data
            $mediations = $section->saleContract->mediations ?? [];
            foreach ($mediations as $mediation) {
                $section_i['mediations'][] = [
                    'id' => $mediation->id,
                    'budget' => 0,
                    'decided' => $mediation->reward,
                    'payee' => $mediation->trader,
                    'paid' => $mediation->reward,
                    'date' => format_date($mediation->date ?? null),
                    'bank' => $mediation->bank,
                    'taxfree' => '',
                    'status' => $mediation->status
                ];
            }

            // Loop sale fee prices data
            $fee = $section->saleContract->fee;
            $fee_prices = $section->saleContract->fee->prices ?? [];
            foreach ($fee_prices as $price) {
                $section_i['fees'][] = [
                    'id' => $price->id,
                    'budget' => 0,
                    'decided' => $price->price,
                    'payee' => $fee->customer,
                    'paid' => $price->price,
                    'date' => format_date($price->date ?? null),
                    'bank' => $price->account,
                    'taxfree' => '',
                    'status' => $price->status
                ];
            }
        }
        if (empty($section_i['mediations'])) {
            $section_i['mediations'][] = [
                'id' => '', 'budget' => 0, 'decided' => '', 'payee' => '', 'paid' => 0,
                'date' => '', 'bank' => '', 'taxfree' => '', 'status' => ''
            ];
        }
        if (empty($section_i['fees'])) {
            $section_i['fees'][] = [
                'id' => '', 'budget' => 0, 'decided' => '', 'payee' => '', 'paid' => 0,
                'date' => '', 'bank' => '', 'taxfree' => '', 'status' => ''
            ];
        }
        $sections->put('i', $section_i);

        // ------------------------------------------------------------------
        // Section j
        // ------------------------------------------------------------------
        $section_j = new \stdClass;
        $section_j->total_tsubo = 0;
        // loop section data to get total size tsubo
        foreach ($master_sections as $key => $section) {
            $section_j->total_tsubo += floatval($section->size_total);
        }
        $sections->put('j', $section_j);
        // dd( $section_j );

        return $sections;
    }

    // ----------------------------------------------------------------------
    // Get Expense Row Basic Data
    // Return collcetions of expense rows model
    // ----------------------------------------------------------------------
    public function get_basic_data($screen_name, $screen_index, $data_kind = '', $data = [], $source = []){
        $expense = $this->expense;
        $data_source = empty($source) ? $this->expense_rows : $source;
        // ------------------------------------------------------------------
        $default_data = [
            'id' => '', 'pj_expense_id' => $expense->id ?? '',
            'decided' => '', 'payperiod' => '', 'payee' => '', 'note' => '', 'paid' => '',
            'date' => '', 'bank' => '', 'taxfree' => '', 'status' => 0,
            'screen_name'   => $screen_name,
            'screen_index'  => $screen_index,
            'data_kind'     => $data_kind,
            'number' => 1
        ];
        if (!empty($data)) {
            $default_data = array_replace($default_data, $data);
        }
        $default_row[] = $default_data;
        // ------------------------------------------------------------------

        // Check if exist return collection from database or default data
        // ------------------------------------------------------------------
        $exist = $data_source->isNotEmpty();
        if ($exist) {
            $rows = $data_source
                    ->where('screen_index', $screen_index)
                    ->where('screen_name', $screen_name)
                    ->where('data_kind', $data_kind);
            $rows->all();
            // -------------------------------------------------------------
            if ($rows->isNotEmpty()) {
                // map result with number
                $withNumber = $rows->map(function ($row, $index) use($data) {
                    $row['number'] = $index + 1;
                    $row['payee'] = $this->get_master_data('payee', ['id' => $row->payee, 'type' => $row->payee_type]);
                    if(empty($data)) return $row;
                    foreach ($data as $key => $value) {
                        if ($key != 'name') {
                            $row[$key] = $value;
                        }
                    }
                    return $row;
                });
                // return data and reset index values
                return $withNumber->values();
            }else{
                return collect($default_row);
            }
            // -------------------------------------------------------------
        }else{
            return collect($default_row);
        }
        // ------------------------------------------------------------------
    }


    // ----------------------------------------------------------------------
    // Project Expense Update Handler
    // ----------------------------------------------------------------------
    public function update($project_id, Request $request){
        try {
            $this->project = Project::findOrFail($project_id);
            $this->expense = PjExpense::where('project_id', $project_id)->first();

            // --------------------------------------------------------------
            // Expense Data
            // --------------------------------------------------------------
            $data_expense = $request->expense;
            $expense = PjExpense::updateOrCreate([
                'id'         => $data_expense['id'],
                'project_id' => $project_id
            ], $data_expense );
            $this->expense = $expense;
            // --------------------------------------------------------------

            // collect all sections
            $sections = collect($request->sections);

            // --------------------------------------------------------------
            // Section C
            // --------------------------------------------------------------
            foreach ($sections->get('c') as $type => $rows) {
                if ($type == 'data' || $type == 'other') {
                    // loop update or create data on every rows
                    foreach ($rows as $entry) {
                        foreach ($entry as $data) {
                            $data['pj_expense_id'] = $expense->id;
                            // cast payperiod to date format and set default day 01
                            $data['payperiod'] = empty($data['payperiod']) ? $data['payperiod'] : Carbon::createFromFormat('y/m', $data['payperiod'])->setDay(01);
                            // extract payee data
                            $payee = collect($data['payee']);
                            $data['payee'] = $payee->get('id');
                            $data['payee_type'] = $payee->get('type');
                            // update or create pj_expense_row
                            PjExpenseRow::updateOrCreate([
                                'id'            => $data['id'],
                                'pj_expense_id' => $expense->id
                            ], $data );
                        }
                    }
                }
            }
            // --------------------------------------------------------------
            // Section E
            // --------------------------------------------------------------
            foreach ($sections->get('e') as $rows) {
                // loop update or create data on every rows
                foreach ($rows as $entry) {
                    // loop multiple data for 1 index
                    foreach ($entry as $data) {
                        $data['pj_expense_id'] = $expense->id;
                        // cast payperiod to date format and set default day 01
                        $data['payperiod'] = empty($data['payperiod']) ? $data['payperiod'] : Carbon::createFromFormat('y/m', $data['payperiod'])->setDay(01);
                        // extract payee data
                        $payee = collect($data['payee']);
                        $data['payee'] = $payee->get('id');
                        $data['payee_type'] = $payee->get('type');
                        // update or create pj_expense_row
                        PjExpenseRow::updateOrCreate([
                            'id'            => $data['id'],
                            'pj_expense_id' => $expense->id
                        ], $data );
                    }
                }
            }
            // --------------------------------------------------------------
            // Section F
            // --------------------------------------------------------------
            foreach ($sections->get('f') as $rows) {
                // loop update or create data on every rows
                foreach ($rows as $entry) {
                    // loop multiple data for 1 index
                    foreach ($entry as $data) {
                        $data['pj_expense_id'] = $expense->id;
                        // cast payperiod to date format and set default day 01
                        $data['payperiod'] = empty($data['payperiod']) ? $data['payperiod'] : Carbon::createFromFormat('y/m', $data['payperiod'])->setDay(01);
                        // extract payee data
                        $payee = collect($data['payee']);
                        $data['payee'] = $payee->get('id');
                        $data['payee_type'] = $payee->get('type');
                        // update or create pj_expense_row
                        PjExpenseRow::updateOrCreate([
                            'id'            => $data['id'],
                            'pj_expense_id' => $expense->id
                        ], $data );
                    }
                }
            }
            // --------------------------------------------------------------
            // Section G
            // --------------------------------------------------------------
            foreach ($sections->get('g') as $rows) {
                // loop update or create data on every rows
                foreach ($rows as $entry) {
                    // loop multiple data for 1 index
                    foreach ($entry as $data) {
                        $data['pj_expense_id'] = $expense->id;
                        // cast payperiod to date format and set default day 01
                        $data['payperiod'] = empty($data['payperiod']) ? $data['payperiod'] : Carbon::createFromFormat('y/m', $data['payperiod'])->setDay(01);
                        // extract payee data
                        $payee = collect($data['payee']);
                        $data['payee'] = $payee->get('id');
                        $data['payee_type'] = $payee->get('type');
                        // update or create pj_expense_row
                        PjExpenseRow::updateOrCreate([
                            'id'            => $data['id'],
                            'pj_expense_id' => $expense->id
                        ], $data );
                    }
                }
            }
            // --------------------------------------------------------------
            // Section H
            // --------------------------------------------------------------
            foreach ($sections->get('h') as $rows) {
                // loop update or create data on every rows
                foreach ($rows as $entry) {
                    // loop multiple data for 1 index
                    foreach ($entry as $data) {
                        $data['pj_expense_id'] = $expense->id;
                        // cast payperiod to date format and set default day 01
                        $data['payperiod'] = empty($data['payperiod']) ? $data['payperiod'] : Carbon::createFromFormat('y/m', $data['payperiod'])->setDay(01);
                        // extract payee data
                        $payee = collect($data['payee']);
                        $data['payee'] = $payee->get('id');
                        $data['payee_type'] = $payee->get('type');
                        // update or create pj_expense_row
                        PjExpenseRow::updateOrCreate([
                            'id'            => $data['id'],
                            'pj_expense_id' => $expense->id
                        ], $data );
                    }
                }
            }

            // --------------------------------------------------------------
            // return success message with response data
            // --------------------------------------------------------------
            $responses = new \stdClass;
            $responses->expense = $expense;
            $responses->sections = $this->get_sections_data();

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
    // Expense Delete Row Handler
    // ----------------------------------------------------------------------
    public function delete($project_id, Request $request){
        try {

            $expense_row = PjExpenseRow::findOrFail($request->id);
            $expense_row->delete();

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
