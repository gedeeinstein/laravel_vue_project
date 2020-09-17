<?php

use Illuminate\Database\Seeder;

use App\Models\SaleContract as Contract;
use App\Models\SaleContractDeposit as Deposit;

class SaleContractDepositSeeder extends Seeder
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
            // Create sale mediation
            // --------------------------------------------------------------
            $count = rand(1, 3);
            factory( Deposit::class, $count )->create([
                'sale_contract_id' => $contract->id
            ]);
            // --------------------------------------------------------------

            // set sale_contract.delivery_price
            // formula C23-2 ー C23-15(total)
            // -----------------------------------------------------------------
            $deposits = $contract->deposits;
            $deposit_price_total = $deposits->sum('price');
            $contract->delivery_price = $contract->contract_price - $deposit_price_total;
            $contract->save();
            // -----------------------------------------------------------------
        });
        // ------------------------------------------------------------------
    }
}
