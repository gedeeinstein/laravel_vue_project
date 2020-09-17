<?php
// --------------------------------------------------------------------------
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\PjPurchase as Purchase;
use App\Models\PjPurchaseDoc as Doc;
use App\Models\PjPurchaseTarget as Target;
use App\Models\PjPurchaseContract as Contract;
use App\Models\PjPurchaseTargetBuilding as Building;
use App\Models\PjPurchaseContractMediation as PurchaseMediation;
// --------------------------------------------------------------------------
use App\Models\Company as Company;
use App\Models\CompanyBankAccount as BankAccount;

// --------------------------------------------------------------------------
class PjPurchaseContractSeeder extends Seeder {
    // ----------------------------------------------------------------------
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // ----------------------------------------------------------------------
    public function run(){
        // ------------------------------------------------------------------
        $faker = Faker::create();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $projects = Project::all();
        $projects->each( function( $project ) use( $faker ){
            // --------------------------------------------------------------
            $created = $project->created_at;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create purchase
            // --------------------------------------------------------------
            $defaults = array( 'project_id' => $project->id );
            $purchase = factory( Purchase::class )->create( $defaults );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create purchase target
            // --------------------------------------------------------------
            $count = rand( 1, 3 );
            $defaults = array( 'pj_purchase_id' => $purchase->id );
            $targets = factory( Target::class, $count )->create( $defaults );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create purchase doc, contract and building
            // --------------------------------------------------------------
            foreach( $targets as $target ){
                // ----------------------------------------------------------
                $defaults = array( 'pj_purchase_target_id' => $target->id );
                // ----------------------------------------------------------
                factory( Doc::class )->create( $defaults );
                factory( Building::class )->create( $defaults );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Create purchase contract
                // ----------------------------------------------------------
                $randomDate = $faker->dateTimeBetween( $created, 'now' );
                $defaults['contract_payment_date'] = $randomDate;
                factory( Contract::class )->create( $defaults );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Create purchase contract mediation
                // ----------------------------------------------------------
                $purchase_contracts = Contract::all();
                foreach ($purchase_contracts as $key => $purchase_contract) {
                    $companies = Company::where('kind_real_estate_agent', 1)->get()->random(1); // get random data company where kind in house = 1
                    $bank_accounts = BankAccount::all()->random(1); // get random company bank account data
                    $total_data = rand(1, 3);

                    factory(PurchaseMediation::class, $total_data )->create([
                        'pj_purchase_contract_id'   => $purchase_contract->id,
                        'bank'                      => $bank_accounts[0]->id,
                        'trader_company_id'         => $companies[0]->id
                    ]);
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------
    }
}
