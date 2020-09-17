<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
// -----------------------------------------------------------------------------
use App\Models\Company;
// -----------------------------------------------------------------------------
use App\Models\Project;
use App\Models\PjPurchaseSale;
use App\Models\PjPurchaseTarget;
use App\Models\PjPurchaseContract;
// -----------------------------------------------------------------------------
use App\Models\MasSection;
use App\Models\MasSectionPayoff;
// -----------------------------------------------------------------------------
use App\Models\SaleContract;
use App\Models\SaleFee;
use App\Models\SaleFeePrice;
// -----------------------------------------------------------------------------

class MasterSectionPayoffController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->source_data($request->section);
        return view('backend.master.section-payoff.form', (array) $data);
    }

    public function update(Request $request)
    {
        try {
            // -----------------------------------------------------------------
            // update or create process
            // -----------------------------------------------------------------
            foreach ($request->section_payoffs as $key => $section_payoff) {
                $section_payoff = collect($section_payoff);

                // get necessary data
                // -------------------------------------------------------------
                $section_payoff_input = $section_payoff->only([
                    'id', 'mas_section_id', 'company_id',
                    'profit_rate', 'profit_rate_total',
                    'profit', 'adjust', 'adjusted',
                ]);
                // -------------------------------------------------------------

                // update or create mas section payoff
                // -------------------------------------------------------------
                MasSectionPayoff::updateOrCreate(
                    ['id' => $section_payoff_input['id']],
                    $section_payoff_input->all()
                );
                // -------------------------------------------------------------
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

    public function source_data($id)
    {
        // ---------------------------------------------------------------------
        // get data for master section payoff page
        // ---------------------------------------------------------------------
        $companies = Company::where('kind_in_house', 1)->get();
        $section = MasSection::findOrFail($id);

        // get sale contract data with its relation
        // ---------------------------------------------------------------------
        $sale_contract = SaleContract::with([
                'deposits', 'mediations',
                'fee', 'fee.prices',
            ])->where('mas_section_id', $section->id)->first();

        // assign default data if sale contract fee is null
        // ---------------------------------------------------------------------
        if (!$sale_contract->fee) {
            $sale_contract->initial_fee = factory(SaleFee::class)->states('init')->make();
            $sale_contract->initial_fee->initial_prices = factory(SaleFeePrice::class)->states('init')->make();
        }
        // --------------------------------------------------------------------

        // get purchase contract mediation data
        // ---------------------------------------------------------------------
        // $project = $section->plan->setting->project;
        $project = $section->project;
        $purchase_targets = PjPurchaseTarget::where('pj_purchase_id', $project->purchase->id)->get();
        $purchase_contract_mediations = $purchase_targets->map(function ($item, $key) {
            return $item->purchase_contract->purchase_contract_mediations;
        })->flatten();
        // ---------------------------------------------------------------------

        // set section payoff data
        // ---------------------------------------------------------------------
        $section_payoffs = $section->payoffs;
        if ($section_payoffs->isEmpty())
        {
            // create mas section payoff default data
            // -----------------------------------------------------------------
            foreach ($companies as $key => $company) {
                $section_payoffs->push(factory(MasSectionPayoff::class)->states('init')->make([
                    'mas_section_id' => $section->id,
                    'company_id' => $company->id,
                ]));
            }
            // -----------------------------------------------------------------
        }else {
            // compare latest kind in house company data
            // with current section payoff company data
            // create mas section payoff default data if new company found
            // -----------------------------------------------------------------
            $companies_id = $companies->map(function ($company) {
                return $company->id;
            });
            $section_payoffs_id = $section_payoffs->map(function ($section_payoff) {
                return $section_payoff->company_id;
            });
            $new_companies_id = $companies_id->diff($section_payoffs_id);

            foreach ($new_companies_id as $key => $new_company_id) {
                $section_payoffs->push(factory(MasSectionPayoff::class)->states('init')->make([
                    'mas_section_id' => $section->id,
                    'company_id' => $new_company_id,
                ]));
            }
            // -----------------------------------------------------------------
        }
        // ---------------------------------------------------------------------

        $update_url = route('master.section.payoff.update', [
            'project' => $project->id,
            'section' => $section->id,
        ]);

        // ---------------------------------------------------------------------
        // assign data
        // ---------------------------------------------------------------------
        $data = new \stdClass;
        $data->page_title = '区画清算';
        $data->companies = $companies;
        $data->project = $project;
        $data->update_url = $update_url;
        // ---------------------------------------------------------------------
        $data->purchase_sale = $data->project->purchaseSale;
        $data->purchase_contract_mediations = $purchase_contract_mediations;
        // ---------------------------------------------------------------------
        $data->section = $section;
        $data->section_payoffs = $section_payoffs->load('company');
        // ---------------------------------------------------------------------
        $data->sale_contract = $sale_contract;
        // ---------------------------------------------------------------------

        return $data;
    }
}
