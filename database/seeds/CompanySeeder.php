<?php
// --------------------------------------------------------------------------
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyBank;
use App\Models\CompanyOffice;
use App\Models\CompanyBorrower;
use App\Models\CompanyBankAccount;
// --------------------------------------------------------------------------
use App\Models\MasterValue;
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
// --------------------------------------------------------------------------
use App\Helpers\InitialHelper as Initial;
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
class CompanySeeder extends Seeder {
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function run(){
        // ------------------------------------------------------------------
        $faker = Faker::create();
        $types = config('const.COMPANY_TYPES');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $insert = array(
            'name'              => 'Port',
            'name_kana'         => 'Port',
            'name_abbreviation' => 'Port',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        );
        // ------------------------------------------------------------------
        // Company types
        // ------------------------------------------------------------------
        if( !empty( $types )) foreach( $types as $type ){
            $insert[ $type ] = 0;
        }
        // ------------------------------------------------------------------
        $company = new Company();
        $company->insert( $insert );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        factory( Company::class, 30 )->create()->each( function( $company ) use ( $faker ){
            // --------------------------------------------------------------
            // If the company is a bank type, create the banks
            // --------------------------------------------------------------
            if( $company->kind_bank ){
                // ----------------------------------------------------------
                $number = $faker->numberBetween( 2, 5 );
                $banks = factory( CompanyBank::class, $number )->create([ 'company_id' => $company->id ]);
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Update the bank index
                // ----------------------------------------------------------
                $banks->each( function( $bank, $index ){
                    $bank->index = $index + 1;
                    $bank->save();
                });
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // If the company is a real estate agent
            // --------------------------------------------------------------
            if( $company->kind_real_estate_agent ){
                // ----------------------------------------------------------
                $associations = MasterValue::where( 'type', 'realestate_guarantee_association' )->get();
                if( !$associations->isEmpty()){
                    // ------------------------------------------------------
                    $random = $associations->random();
                    $company->real_estate_guarantee_association = $random->key;
                    // ------------------------------------------------------
                    if( 'other' == $company->real_estate_guarantee_association ){
                        $company->real_estate_agent_depositary_name = $faker->company;
                        $company->real_estate_agent_depositary_name_address = $faker->streetAddress;
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Save all company changes
            // --------------------------------------------------------------
            $company->save();
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // If the company is in-house group
        // ------------------------------------------------------------------
        $banks = CompanyBank::with( 'company' )->get();
        $groupCompanies = Company::where( 'kind_in_house', true )->get();
        // ------------------------------------------------------------------
        foreach( $groupCompanies as $company ){
            // --------------------------------------------------------------
            $company->kind_in_house_abbreviation = Initial::generate( $company->name );
            $company->save();
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create bank accounts
            // --------------------------------------------------------------
            $randomBanks = $banks->random( $faker->numberBetween( 2, 5 ));
            // --------------------------------------------------------------
            $randomBanks->each( function( $bank, $index) use ( $company ){
                $presets = array( 'company_id' => $company->id, 'bank_id' => $bank->id, 'index' => $index + 1 );
                factory( CompanyBankAccount::class )->create( $presets );
            });
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create borrowers
            // --------------------------------------------------------------
            $randomBanks = $banks->random( $faker->numberBetween( 2, 5 ));
            // --------------------------------------------------------------
            $randomBanks->each( function( $bank, $index ) use ( $company ){
                $presets = array( 'company_id' => $company->id, 'bank_id' => $bank->id, 'index' => $index + 1 );
                factory( CompanyBorrower::class )->create( $presets );
            });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // If company is a real-estate agent
        // ------------------------------------------------------------------
        $query = MasterValue::where( 'type', 'realestate_license_organ' );
        $authorizers = $query->orderBy( 'sort', 'asc' )->get();
        // ------------------------------------------------------------------
        $agentCompanies = Company::where( 'kind_real_estate_agent', true )->get();
        if( !$agentCompanies->isEmpty()) foreach( $agentCompanies as $company ){
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // License authorizer ID
            // --------------------------------------------------------------
            if( !$authorizers->isEmpty()){
                $company->license_authorizer_id = $authorizers->random()->id;
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Save company updates
            // --------------------------------------------------------------
            $company->save();
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create offices
            // --------------------------------------------------------------
            $amount = $faker->numberBetween( 1, 3 );
            $presets = array( 'company_id' => $company->id );
            $offices = factory( CompanyOffice::class, $amount )->create( $presets );
            // --------------------------------------------------------------
            $offices->each( function( $office, $index ){
                $office->index = $index +1;
                $office->save(); 
            });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
