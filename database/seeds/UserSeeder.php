<?php
// --------------------------------------------------------------------------
use App\Models\User;
use App\Models\UserRole;
use App\Models\Company;
// --------------------------------------------------------------------------
use Faker\Factory as Faker;
// --------------------------------------------------------------------------
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class UserSeeder extends Seeder {
    // ----------------------------------------------------------------------
    public function run(){
        // ------------------------------------------------------------------
        // Preset users
        // ------------------------------------------------------------------
        $presets = array(
            'admin' => array(
                'user_role_id'    => 6,
                'company_id'      => null,
                'first_name'      => 'Admin',
                'last_name'       => 'Global',
                'first_name_kana' => '全体',
                'last_name_kana'  => '管理者',
                'nickname'        => 'admin-global',
                'email'           => 'admin@admin.com'
            ),
            'general' => array(
                'user_role_id'    => 2,
                'company_id'      => 1,
                'first_name'      => 'User',
                'last_name'       => 'General',
                'nickname'        => 'general-user',
                'email'           => 'general@company.com'
            ),
            'ledger_editor' => array(
                'user_role_id'    => 4,
                'company_id'      => 2,
                'first_name'      => 'Editor',
                'last_name'       => 'Ledger',
                'nickname'        => 'ledger-editor',
                'email'           => 'ledger.editor@company.com'
            ),
            'registration_manager' => array(
                'user_role_id'    => 5,
                'company_id'      => 3,
                'first_name'      => 'Manager',
                'last_name'       => 'Registration',
                'nickname'        => 'registration-manager',
                'email'           => 'registration.manager@company.com'
            ),
            'accountant' => array(
                'user_role_id'    => 8,
                'company_id'      => 4,
                'first_name'      => 'Accountant',
                'last_name'       => 'Accountant',
                'nickname'        => 'accountant',
                'email'           => 'accountant@company.com'
            ),
            'accounting_firm' => array(
                'user_role_id'    => 7,
                'company_id'      => 5,
                'first_name'      => 'Firm',
                'last_name'       => 'Accounting',
                'nickname'        => 'accounting-firm',
                'email'           => 'accounting.firm@company.com'
            ),
            'agent' => array(
                'user_role_id'    => 3,
                'company_id'      => 6,
                'first_name'      => 'Agent',
                'last_name'       => 'Agent',
                'nickname'        => 'agent',
                'email'           => 'agent@company.com'
            ),
            'restricted' => array(
                'user_role_id'    => 1,
                'company_id'      => 7,
                'first_name'      => 'User',
                'last_name'       => 'Restricted',
                'nickname'        => 'restricted',
                'email'           => 'restricted@company.com'
            ),
            'default' => array(
                'user_role_id'    => 2,
                'company_id'      => 1,
                'first_name'      => 'User',
                'last_name'       => 'Default',
                'nickname'        => 'default-user',
                'email'           => 'user@company.com'
            )
        );
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        DB::transaction( function() use( $presets ){
            // --------------------------------------------------------------
            $user      = new User();
            $roles     = UserRole::all();
            $companies = Company::all();
            // --------------------------------------------------------------
            $faker = Faker::create('ja_JP');
            $usFaker = Faker::create('en_US');
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create preset users
            // --------------------------------------------------------------
            foreach( $presets as $user ){
                factory( User::class )->create( $user );
            }
            // --------------------------------------------------------------
    
            
            // --------------------------------------------------------------
            // Create user sample data for each company
            // --------------------------------------------------------------
            $companies->each( function( $company ) use( $faker, $roles ){
                $members = $faker->numberBetween( 2, 6 );
                // ----------------------------------------------------------
    
                // ----------------------------------------------------------
                $append = array( 'company_id' => $company->id );
                $users = factory( User::class, $members )->create( $append );
                // ----------------------------------------------------------
                $users->each( function( $user ) use( $faker, $company, $roles ){
                    // ------------------------------------------------------
                    $role = $roles->random();
                    $user->user_role_id = (int) $role->id;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Randomize the notary registration flag
                    // ------------------------------------------------------
                    if( $company->kind_in_house || $company->kind_real_estate_agent ){
                        $user->real_estate_notary_registration = $faker->boolean();
                    }
                    // ------------------------------------------------------
    
                    // ------------------------------------------------------
                    $user->save(); // Save the updates
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------
    
    
            // --------------------------------------------------------------
            // Create individual users
            // --------------------------------------------------------------
            $append =  array( 'company_id' => null );
            $individuals = factory( User::class, 6 )->create( $append );
            // --------------------------------------------------------------
            $individuals->each( function( $user ) use( $roles ){
                // ----------------------------------------------------------
                $role = $roles->random();
                $user->user_role_id = (int) $role->id;
                // ----------------------------------------------------------
                $user->save(); // Save the updates
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------
    }
}
// --------------------------------------------------------------------------