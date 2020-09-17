<?php
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
// --------------------------------------------------------------------------
use App\Models\Company as Company;
use App\Models\CompanyBankAccount as BankAccount;
use App\Models\Project;
// --------------------------------------------------------------------------
use App\Models\PjPurchase as Purchase;
use App\Models\PjPurchaseTarget as Target;
use App\Models\PjPurchaseTargetBuilding as TargetBuilding;
use App\Models\PjPurchaseTargetContractor as TargetContractor;
use App\Models\PjPurchaseContractMediation as PurchaseMediation;
// --------------------------------------------------------------------------
use App\Models\PjPropertyOwner as PropertyOwner;
// --------------------------------------------------------------------------
use App\Models\PjLotCommon as Common;
use App\Models\PjLotRoadOwner as RoadOwner;
use App\Models\PjLotContractor as Contractor;
use App\Models\PjLotBuildingOwner as BuildingOwner;
use App\Models\PjLotResidentialOwner as ResidentialOwner;
// --------------------------------------------------------------------------
use App\Models\PjPurchaseDoc as Doc;
use App\Models\PjPurchaseSale as Sale;
use App\Models\PjPurchaseContract as Contract;
// --------------------------------------------------------------------------
use App\Models\TradingLedger;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PjPurchaseSeeder extends Seeder {
    // ----------------------------------------------------------------------
    public function run(){
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Create lot common and contractors
        // ------------------------------------------------------------------
        $propertyOwners = PropertyOwner::where( 'pj_property_id', 1 )->get();
        $propertyOwners->each( function( $propertyOwner ){
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Residential owners
            // --------------------------------------------------------------
            $residentialOwners = ResidentialOwner::where( 'pj_property_owners_id', $propertyOwner->id )->get();
            $residentialOwners->each( function( $residentialOwner ) use( $propertyOwner ){
                // ----------------------------------------------------------
                $residentialID = $residentialOwner->pj_lot_residential_a_id;
                $common = factory( Common::class )->create([
                    'pj_property_id' => 1,
                    'pj_lot_residential_a_id' => $residentialID
                ]);
                // ----------------------------------------------------------
                Contractor::create([
                    'name'  => $propertyOwner->name,
                    'same_to_owner' => 1,
                    'pj_lot_common_id' => $common->id,
                    'pj_property_owner_id' => $propertyOwner->id,
                ]);
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Road owners
            // --------------------------------------------------------------
            $roadOwners = RoadOwner::where( 'pj_property_owners_id', $propertyOwner->id )->get();
            $roadOwners->each( function( $roadOwner ) use( $propertyOwner ){
                // ----------------------------------------------------------
                $roadID = $roadOwner->pj_lot_road_a_id;
                $common = factory( Common::class )->create([
                    'pj_property_id' => 1,
                    'pj_lot_road_a_id' => $roadID
                ]);
                // ----------------------------------------------------------
                Contractor::create([
                    'name'  => $propertyOwner->name,
                    'same_to_owner' => 1,
                    'pj_lot_common_id' => $common->id,
                    'pj_property_owner_id' => $propertyOwner->id,
                ]);
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Building owners
            // --------------------------------------------------------------
            $buildingOwners = BuildingOwner::where( 'pj_property_owners_id', $propertyOwner->id )->get();
            $buildingOwners->each( function( $buildingOwner ) use( $propertyOwner ){
                // ----------------------------------------------------------
                $buildingID = $buildingOwner->pj_lot_building_a_id;
                $common = factory( Common::class )->create([
                    'pj_property_id' => 1,
                    'pj_lot_building_a_id' => $buildingID
                ]);
                // ----------------------------------------------------------
                Contractor::create([
                    'name'  => $propertyOwner->name,
                    'same_to_owner' => 1,
                    'pj_lot_common_id' => $common->id,
                    'pj_property_owner_id' => $propertyOwner->id,
                ]);
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Iterate each projects
        // ------------------------------------------------------------------
        $projects = Project::all();
        $projects->each( function( $project ) use( $propertyOwners ){
            // --------------------------------------------------------------
            $count = rand( 1, 3 );

            if ($project->id == 1 || $project->id == 2) $count = 1;

            $dataset = array( 'project_id' => $project->id, 'count' => $count );
            $purchase = Purchase::create( $dataset );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $targets = factory( Target::class, $count )->create([ 'pj_purchase_id' => $purchase->id ]);
            $targets->each( function( $target ) use( $project, $propertyOwners ){
                // ----------------------------------------------------------
                $faker = Faker::create();
                $created = $project->created_at;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Target contractors
                // ----------------------------------------------------------
                if ($project->id == 1) {
                    $propertyOwners->each( function( $propertyOwner ) use( $target ){
                        // ------------------------------------------------------
                        $contractor = Contractor::where( 'name', $propertyOwner->name )->first();
                        if( $contractor ) TargetContractor::create([
                            'user_id'                   => 1,
                            'pj_purchase_target_id'     => $target->id,
                            'pj_lot_contractor_id'      => $contractor->id
                        ]);
                        // ------------------------------------------------------
                    });
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Target building
                // ----------------------------------------------------------
                TargetBuilding::create([
                    'kind'                           => 1,
                    'exist_unregistered'             => 1,
                    'purchase_third_person_occupied' => 2,
                    'pj_purchase_target_id'          => $target->id
                ]);
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Purchase doc
                // ----------------------------------------------------------
                $insert = array( 'pj_purchase_target_id' => $target->id );
                factory( Doc::class )->create( $insert );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Purchase contract
                // ----------------------------------------------------------
                $contractDate = $faker->dateTimeBetween( $created, 'now' );
                $insert['contract_date'] = $contractDate;
                // ----------------------------------------------------------
                $randomDays = rand( 1, 30 );
                $paymentDate = Carbon::parse( $contractDate )->addDays( $randomDays );
                $insert['contract_payment_date'] = $paymentDate;
                // ----------------------------------------------------------
                $insert['seller_broker_company_id'] = Company::where('kind_real_estate_agent', 1)->get()->random()->id;
                // ----------------------------------------------------------
                factory( Contract::class )->create( $insert );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Trading ledger
                // ----------------------------------------------------------
                $sale = Sale::where( 'project_id', $project->id )->first();
                if( $sale ) TradingLedger::create([
                    'status'                => 1,
                    'ledger_type'           => 1,
                    'sale_contract_id'      => null,
                    'pj_purchase_sale_id'   => $sale->id,
                    'pj_purchase_target_id' => $target->id,
                ]);
            });
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------
        // ----------------------------------------------------------
        // Purchase contract mediation
        // ----------------------------------------------------------
        $purchase_contracts = Contract::all();
        foreach ($purchase_contracts as $key => $purchase_contract) {
            $company = Company::where('kind_real_estate_agent', 1)->get()->random(); // get random data company where kind real estate agent = 1
            $bank_account = BankAccount::all()->random(); // get random company bank account data
            $total_data = rand(1, 3);

            factory(PurchaseMediation::class, $total_data )->create([
                'pj_purchase_contract_id'   => $purchase_contract->id,
                'bank'                      => $bank_account->id,
                'trader_company_id'         => $company->id
            ]);
        }
        // ----------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
