<?php
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Database\Seeder;
// --------------------------------------------------------------------------
use App\Models\Company as Company;
use App\Models\SaleContract as Contract;
use App\Models\SaleMediation as Mediation;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class SaleMediationSeeder extends Seeder {
    public function run(){
        // ------------------------------------------------------------------
        $contracts = Contract::all();
        $contracts->each( function( $contract ){
            $company = Company::where('kind_real_estate_agent', 1)->get()->random(); // get real estate company
            // --------------------------------------------------------------
            // Create sale mediation
            // --------------------------------------------------------------
            $count = rand(1, 3);
            factory( Mediation::class, $count )->create([
                'sale_contract_id' => $contract->id,
                'trader'           => $company->id
            ]);
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------
    }
}
// --------------------------------------------------------------------------
