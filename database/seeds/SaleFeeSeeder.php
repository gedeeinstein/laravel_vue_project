<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Seeder;
// --------------------------------------------------------------------------
use App\Models\Company as Company;
// --------------------------------------------------------------------------
use App\Models\SaleFee as Fee;
use App\Models\SaleFeePrice as Price;
use App\Models\SaleContract as Contract;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class SaleFeeSeeder extends Seeder {
    public function run(){
        // ------------------------------------------------------------------
        $contracts = Contract::all();
        $contracts->each( function( $contract ){
            $company = Company::where('kind_in_house', 1)->get()->random(); // get kind in house company
            // --------------------------------------------------------------
            // Create Sale Fee & Prices
            // --------------------------------------------------------------
            $fee = factory( Fee::class )->create([
                'sale_contract_id' => $contract->id,
                'receipt_company'  => $company->id
            ]);
            // --------------------------------------------------------------
            $count = rand(1, 3);
            factory( Price::class, $count )->create([
                'sale_fee_id'      => $fee->id,
                'sale_contract_id' => $contract->id
            ]);
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------
    }
}
// --------------------------------------------------------------------------
