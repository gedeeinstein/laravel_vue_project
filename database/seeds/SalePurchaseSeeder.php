<?php

use Illuminate\Database\Seeder;
// -----------------------------------------------------------------------------
use App\Models\SaleContract as Contract;
use App\Models\SalePurchase as Purchase;
// -----------------------------------------------------------------------------

class SalePurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ------------------------------------------------------------------
        $contracts = Contract::all();
        $contracts->each( function( $contract ){
            // --------------------------------------------------------------
            // Create sale purchase
            // --------------------------------------------------------------
            $count = rand(1, 2);
            factory( Purchase::class, $count )->create([
                'sale_contract_id' => $contract->id
            ]);
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------
    }
}
