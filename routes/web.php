<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|

// Auth::routes();
// Registration Routes...
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');

*/
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// All available roles
// --------------------------------------------------------------------------
$role = (object) array(
    'admin'      => 'global_admin',
    'general'    => 'general',
    'ledger'     => 'ledger_editor',
    'manager'    => 'registration_manager',
    'accountant' => 'accountant',
    'accounting' => 'accounting_firm',
    'agent'      => 'agent',
    'restricted' => 'no_access'
);
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
Route::get('/', function(){
    return redirect('login');
})->name('index');
// --------------------------------------------------------------------------
// User authentication routes
// --------------------------------------------------------------------------
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
// --------------------------------------------------------------------------
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
// --------------------------------------------------------------------------
// Password Reset Routes
// --------------------------------------------------------------------------
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
// --------------------------------------------------------------------------
// Email Verification Routes
// --------------------------------------------------------------------------
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
// --------------------------------------------------------------------------

/*
// --------------------------------------------------------------------------
| Route groups backend authenticated
// --------------------------------------------------------------------------
*/
Route::group([ 'middleware' => 'auth:web' ], function() use( $role ){

    // set app language
    // ----------------------------------------------------------------------
    Route::get('lang/{language}', function($language){
        $langs = ['en', 'jp'];
        if(in_array($language, $langs)){
            request()->session()->put('language', $language);
            return redirect()->back();
        }
        abort(404);
    })->name('setlanguage');
    // ----------------------------------------------------------------------

    // sample common component
    Route::get('sample/{page}', function($page){
        return view("backend.samples.{$page}", ['page_title' => "Sample Page {$page}"]);
    })->name('sample');
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Backend namespace routes
    // Restrict access based on spec at https://bit.ly/3dYqA7M
    // ----------------------------------------------------------------------
    $user = collect( $role )->except([ 'agent', 'restricted' ]);
    Route::namespace('Backend')->middleware([ "role:{$user->join(',')}", 'preset' ])->group( function() use( $role ){
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Dashboard
        // ------------------------------------------------------------------
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Master Data & Activity Log
        // Restrict access based on spec below
        // https://bit.ly/2wXhurx, https://bit.ly/3aNxx9N
        // ------------------------------------------------------------------
        $user = collect([ $role->admin ]);
        Route::middleware("role:{$user->join(',')}")->group( function(){
            // --------------------------------------------------------------
            // Master Values
            // --------------------------------------------------------------
            Route::resource('master/values', 'MasterValuesController', [ 'as' => 'master' ])->only([ 'index', 'show' ]);
            // --------------------------------------------------------------
            // Master Region
            // --------------------------------------------------------------
            Route::redirect('master/region', '/master/region/01/area' );
            Route::redirect('master/region/01', '/master/region/01/area' );
            Route::resource('master/region/{prefecture}/area', 'MasterRegionsController', [ 'as' => 'master' ])->only([ 'index', 'show' ]);
            // --------------------------------------------------------------
            // Activity Logs
            // --------------------------------------------------------------
            Route::resource('logs', 'LogActivityUserController')->only([ 'index', 'show' ])->names([ 'index' => 'logs.index' ]);
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------


        //-------------------------------------------------------------------
        // Company & Users
        // Restrict access based on spec at https://bit.ly/3e0u8qn
        //-------------------------------------------------------------------
        $user = collect( $role )->except( 'accounting' );
        Route::middleware("role:{$user->join(',')}")->group( function(){
            Route::resource('company', 'CompanyController');
            Route::get('company/0/user', 'UserIndividualController@index')->name('user.individual.index');
            Route::post('company/0/user', 'UserIndividualController@store')->name('user.individual.store');
            Route::get('company/0/user/create', 'UserIndividualController@create')->name('user.individual.create');
            Route::match(['put', 'patch'], 'company/0/user/{user}', 'UserIndividualController@update')->name('user.individual.update');
            Route::get('company/0/user/{user}', 'UserIndividualController@show')->name('user.individual.show');
            Route::get('company/0/user/{user}/edit', 'UserIndividualController@edit')->name('user.individual.edit');
            Route::delete('company/0/user/{user}', 'UserIndividualController@destroy')->name('user.individual.destroy');
            Route::resource('company.user', 'UserController');
            Route::resource('user', 'UserMasterController');
        });
        //-------------------------------------------------------------------


        //-------------------------------------------------------------------
        // Q&A Manager & Q&A Manager Category
        // Restrict access based on spec at https://bit.ly/2UKXSQa
        //-------------------------------------------------------------------
        $editor = collect([ $role->admin ]);
        $viewer = collect( $role )->except( 'accounting' );
        //-------------------------------------------------------------------
        Route::middleware("role:{$viewer->join(',')}")->group( function() use( $editor ){
            //---------------------------------------------------------------
            // Only those in the editor list can access the edit & create page
            //---------------------------------------------------------------
            Route::middleware("role:{$editor->join(',')}")->group( function(){
                Route::resource('qamanager', 'QAManagerController');
                Route::post('qamanager/order', 'QAManagerController@order')->name('qamanager.order');
                Route::resource('qamanager-category', 'QAManagerCategoryController');
                Route::post('qamanager-category/order', 'QAManagerCategoryController@order')->name('qamanager-category.order');
            });
            //---------------------------------------------------------------

            //---------------------------------------------------------------
            // All other viewers granted only view access
            //---------------------------------------------------------------
            Route::resource('qamanager', 'QAManagerController')->only([ 'index', 'show' ]);
            Route::resource('qamanager-category', 'QAManagerCategoryController')->only([ 'index', 'show' ]);
            //---------------------------------------------------------------
        });
        //-------------------------------------------------------------------


        //-------------------------------------------------------------------
        // Project group
        //-------------------------------------------------------------------
        Route::namespace('Project')->group( function() use( $role ){
            //---------------------------------------------------------------
            // Project Sheet Manager
            // Restrict access based on spec at https://bit.ly/2wl03Ri
            //---------------------------------------------------------------
            $user = collect([ $role->admin ]);
            Route::middleware("role:{$user->join(',')}")->group( function(){
                Route::get('project/sheet-manager', 'ProjectSheetManagerController@index')->name('project.sheet-manager');
                Route::post('project/sheet-manager', 'ProjectSheetManagerController@update')->name('project.sheet-manager.update');
            });
            //---------------------------------------------------------------


            //---------------------------------------------------------------
            // Project List routes
            //---------------------------------------------------------------
            Route::get('project/list', 'ProjectListController@index')->name('project.list.index');
            Route::post('project/list', 'ProjectListController@filter')->name('project.list.filter');
            Route::get('project/list/{any}', 'ProjectListController@index')->where('any', '.*');
            //---------------------------------------------------------------
            Route::put('project/create', 'ProjectListController@create')->name('project.create');
            Route::delete('project/delete/{project?}', 'ProjectListController@delete')->name('project.delete');
            //---------------------------------------------------------------


            //---------------------------------------------------------------
            // Project memo routes
            //---------------------------------------------------------------
            Route::put('project/memo', 'ProjectMemoController@create')->name('project.memo.create');
            Route::post('project/memo', 'ProjectMemoController@update')->name('project.memo.update');
            Route::delete('project/memo/{id}', 'ProjectMemoController@delete')->name('project.memo.delete');
            //---------------------------------------------------------------


            //---------------------------------------------------------------
            // PJ Sheet routes
            //---------------------------------------------------------------
            Route::get('project/{project}/sheet', 'ProjectSheetController@index')->name('project.sheet');
            Route::post('project/{project}/sheet', 'ProjectSheetController@update')->name('project.sheet.update');
            Route::delete('project/{project}/sheet', 'ProjectSheetController@delete')->name('project.sheet.delete');
            Route::get('project/{project}/sheet/{any}', 'ProjectSheetController@index')->where('any', '.*');
            //---------------------------------------------------------------
            Route::delete('project/sheet/expense/{id}', 'ProjectSheetController@delete_expense')->name('project.sheet.expense.delete');
            //---------------------------------------------------------------


            //---------------------------------------------------------------
            // Pj Assist routes
            //---------------------------------------------------------------
            Route::get('project/{project}/assist/a', 'ProjectAssistController@assist_a')->name('project.assist.a');
            Route::post('project/{project}/assist/a', 'ProjectAssistController@assist_a_update')->name('project.assist.a.update');
            Route::delete('project/{project}/assist/a/{type}', 'ProjectAssistController@assist_a_delete')->name('project.assist.a.delete');
            //---------------------------------------------------------------
            Route::get('project/{project}/assist/b', 'ProjectAssistController@assistB')->name('project.assist.b');
            Route::post('project/{project}/assist/b', 'ProjectAssistController@assist_b_update')->name('project.assist.b.update');
            //---------------------------------------------------------------
            // Route::get('project/{project}/template', 'ProjectTemplateController@index')->name('project.template');
            Route::get('project/{project}/purchases-sales', 'ProjectPurchaseSalesController@create')->name('project.purchases-sales');
            Route::post('project/{project}/purchases-sales', 'ProjectPurchaseSalesController@update')->name('project.purchases-sales.update');
            Route::delete('project/{project}/purchases-sales/{type}', 'ProjectPurchaseSalesController@delete')->name('project.purchases-sales.delete');
            //-----------------------------------------------------------
            $allowed_roles = collect( $role )->except( 'accounting' );
            Route::middleware("role:{$allowed_roles->join(',')}")->group( function(){
                Route::get('project/{project}/{purchase_target}/purchase-create', 'ProjectPurchaseCreateController@purchase_create')->name('project.purchase.create');
                Route::post('project/{project}/{purchase_target}/purchase-create', 'ProjectPurchaseCreateController@update')->name('project.purchase.create.update');
                Route::delete('project/{project}/purchase-create', 'ProjectPurchaseCreateController@delete')->name('project.purchase.create.delete');
                Route::get('project/{project}/purchase-create/agreement-list', 'ProjectPurchaseCreateController@agreement_list')->name('project.purchase.create.agreement-list');
            });
            // ----------------------------------------------------------
            Route::get('project/{project}/purchase-contract', 'ProjectPurchaseContractController@contract')->name('project.purchase.contract');
            Route::post('project/{project}/purchase-contract', 'ProjectPurchaseContractController@update')->name('project.purchase.contract.update');
            Route::delete('project/{project}/purchase-contract/{type}', 'ProjectPurchaseContractController@delete')->name('project.purchase.contract.delete');
            //-----------------------------------------------------------
            $allowed_roles = collect( $role )->except( 'accounting' );
            Route::middleware("role:{$allowed_roles->join(',')}")->group( function(){
                Route::resource('pj-purchase-response', 'ProjectPurchaseResponseController');
                Route::get('project/{project}/purchase/{purchase_target}/response', 'ProjectPurchaseResponseController@response')->name('project.purchase.response');
                Route::post('project/{project}/purchase/{purchase_target}/response', 'ProjectPurchaseResponseController@store')->name('project.purchase.response.store');
            });
            //-----------------------------------------------------------
            Route::get('project/{project}/purchase', 'ProjectPurchaseController@form')->name('project.purchase');
            //-----------------------------------------------------------
            Route::get('project/{project}/purchase/target/{target}/contract-create', 'ProjectPurchaseContractCreateController@index')->name('project.purchase.target.contract.create');
            Route::post('project/{project}/purchase/target/{target}/contract-create', 'ProjectPurchaseContractCreateController@update')->name('project.purchase.target.contract.update');
            //-----------------------------------------------------------
            Route::post('project/{project}/purchase/store', 'ProjectPurchaseController@save_purchases')->name('project.purchase.store');
            Route::post('project/{project}/purchase/count/store', 'ProjectPurchaseController@save_count')->name('project.purchase.count.store');
            Route::post('project/{project}/purchase/contractor/store', 'ProjectPurchaseController@save_contractors')->name('project.purchase.contractor.store');
            Route::get('project/{project}/purchase/api', 'ProjectPurchaseController@formApi')->name('project.purchase.api');
            //---------------------------------------------------------------
            Route::get('project/{project}/expense', 'ProjectExpenseController@index')->name('project.expense');
            Route::post('project/{project}/expense', 'ProjectExpenseController@update')->name('project.expense.update');
            Route::delete('project/{project}/expense', 'ProjectExpenseController@delete')->name('project.expense.delete');
            //---------------------------------------------------------------
            Route::get('project/{project}/ledger', function(){ return 'Under Development'; })->name('project.ledger');

            //---------------------------------------------------------------
            // Reports
            //---------------------------------------------------------------
            Route::post('project/{project}/report/{report}', 'ProjectSheetController@report')->name('project.sheet.report');
            Route::post('project/{project}/target/{target}/report/{report?}', 'ProjectPurchaseContractCreateController@report')->name('project.purchase.create.report');
            Route::post('project/{project}/report/{target}/notes', 'ProjectPurchaseCreateController@report')->name('project.purchase.create.create.report');
            Route::post('project/{project}/report/{target}/contract', 'ProjectPurchaseCreateController@reportContract')->name('project.purchase.create.contract.report');
            //---------------------------------------------------------------


            //---------------------------------------------------------------
            // Project approval request
            //---------------------------------------------------------------
            // Route::middleware("role:global_admin")->group( function(){
            //     Route::post('project/{project}/approval', 'ProjectController@approval')->name('project.approval');
            // });
            //---------------------------------------------------------------
            // Route::post('project/{project}/approval/request', 'ProjectSheetController@approval_request')->name('project.approval.request');
            //---------------------------------------------------------------


            //---------------------------------------------------------------
            // Project request inspection request routes
            //---------------------------------------------------------------
            Route::post('project/{project}/request/{type?}', 'ProjectInspectionController@request')->name('project.inspection.request');
            //---------------------------------------------------------------
            Route::middleware("role:global_admin")->group( function(){
                Route::get('project/inspection', 'ProjectInspectionController@index')->name('project.inspection.index');
                Route::post('project/inspection', 'ProjectInspectionController@filter')->name('project.inspection.filter');
                Route::post('project/inspection/{id?}', 'ProjectInspectionController@update')->name('project.inspection.update');
                Route::get('project/inspection/{any}', 'ProjectInspectionController@index')->where('any', '.*');
            });
            //---------------------------------------------------------------
        });
        //-------------------------------------------------------------------

        //-------------------------------------------------------------------
        // Master group
        //-------------------------------------------------------------------
        Route::namespace('Master')->group( function() use( $role ){
            //---------------------------------------------------------------
            // Master Finance
            //---------------------------------------------------------------
            Route::get('master/{project}/finance', 'MasterFinanceController@index')->name('master.finance');
            Route::post('master/{project}/finance', 'MasterFinanceController@update')->name('master.finance.update');
            Route::delete('master/{project}/finance', 'MasterFinanceController@delete')->name('master.finance.delete');
            //---------------------------------------------------------------

            //---------------------------------------------------------------
            // Master Basic
            //---------------------------------------------------------------
            Route::middleware("role:global_admin")->group( function(){
                Route::get('master/{project}/basic', 'MasterBasicController@index')->name('master.basic');
                Route::post('master/{project}/basic', 'MasterBasicController@update')->name('master.basic.update');
                Route::delete('master/{project}/basic', 'MasterBasicController@delete')->name('master.basic.delete');
            });
            //---------------------------------------------------------------
            // -----------------------------------------------------------------
            // Master Section Payoff
            // -----------------------------------------------------------------
            Route::middleware("role:global_admin")->group( function(){
                Route::get('master/{project}/section/{section}/payoff', 'MasterSectionPayoffController@index')->name('master.section.payoff');
                Route::post('master/{project}/section/{section}/payoff', 'MasterSectionPayoffController@update')->name('master.section.payoff.update');
            });
            // -----------------------------------------------------------------
            //---------------------------------------------------------------
            // Master Section List
            //---------------------------------------------------------------
            Route::get('master/section/list', 'MasterSectionListController@index')->name('master.section.list.index');
            Route::post('master/section/list', 'MasterSectionListController@filter')->name('master.section.list.filter');
            Route::get('master/section/list/{any}', 'MasterSectionListController@index')->where('any', '.*');
            //---------------------------------------------------------------

            //---------------------------------------------------------------
            // Master Purchase List (B)
            //---------------------------------------------------------------
            Route::get('master/purchase/list', 'MasterListController@index')->name('master.purchase.list.index');
            Route::post('master/purchase/list', 'MasterListController@filter')->name('master.purchase.list.filter');
            Route::get('master/purchase/list/{any}', 'MasterListController@index')->where('any', '.*');
            //---------------------------------------------------------------

            //---------------------------------------------------------------
            // Mater Purchase memo routes
            //---------------------------------------------------------------
            Route::put('master/purchase-list/memo', 'MasterListMemoController@create')->name('master.purchase.memo.create');
            Route::post('master/purchase-list/memo', 'MasterListMemoController@update')->name('master.purchase.memo.update');
            Route::delete('master/purchase-list/memo/{id}', 'MasterListMemoController@delete')->name('master.purchase.memo.delete');
            //---------------------------------------------------------------

            //---------------------------------------------------------------
            // Sale List (C)
            //---------------------------------------------------------------
            Route::get('master/sale/list', 'MasterSaleListController@index')->name('master.sale.list.index');
            Route::post('master/sale/list', 'MasterSaleListController@filter')->name('master.sale.list.filter');
            Route::get('master/sale/list/{any}', 'MasterSaleListController@index')->where('any', '.*');
            //---------------------------------------------------------------

            //---------------------------------------------------------------
            // Sale memo routes
            //---------------------------------------------------------------
            Route::put('master/sale-list/memo', 'MasterSaleListMemoController@create')->name('master.sale.memo.create');
            Route::post('master/sale-list/memo', 'MasterSaleListMemoController@update')->name('master.sale.memo.update');
            Route::delete('master/sale-list/memo/{id}', 'MasterSaleListMemoController@delete')->name('master.sale.memo.delete');
            //---------------------------------------------------------------

            //---------------------------------------------------------------
            Route::get('master/{project}/section-payoff', function() {
                $data = new \stdClass;
                $data->page_title = "test";
                return view('backend.master.section-payoff.form', (array) $data);
            });
            //---------------------------------------------------------------
        });
        // ---------------------------------------------------------------------

        //-------------------------------------------------------------------
        // Sale group
        //-------------------------------------------------------------------
        Route::namespace('Sale')->group( function() use( $role ){
            // -----------------------------------------------------------------
            // Sale Contract
            // -----------------------------------------------------------------
            Route::get('sale/{project}/section/{section}/contract', 'SaleContractController@index')->name('sale.contract');
            Route::get('sale/{project}/section/{section}/contract/tab/{purchase}', 'SaleContractController@index')->name('sale.contract.tab');
            Route::post('sale/{project}/section/{section}/contract', 'SaleContractController@update')->name('sale.contract.update');
            Route::delete('sale/{project}/section/{section}/contract/{type}', 'SaleContractController@delete')->name('sale.contract.delete');
            // -----------------------------------------------------------------
        });
        // ---------------------------------------------------------------------

    });
});
