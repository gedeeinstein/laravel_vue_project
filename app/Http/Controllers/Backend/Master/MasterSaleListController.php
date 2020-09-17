<?php

namespace App\Http\Controllers\Backend\Master;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
// --------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use App\Http\Controllers\Backend\Project\ProjectExpenseController as ExpenseCtrl;
// --------------------------------------------------------------------------
use App\Models\Project;
use App\Models\User;
use App\Models\Company;
use App\Models\SaleContract;
use App\Models\SaleMemo;
use App\Models\MasSection as Section;
use App\Models\PjPurchaseContract as Contract;
// --------------------------------------------------------------------------

class MasterSaleListController extends Controller
{
    // ----------------------------------------------------------------------
    public function index(){
        // ------------------------------------------------------------------
        $data = new \stdClass;
        $data->user = Auth::user();
        $data->filters = new \stdClass;
        $data->template = new \stdClass;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Create memo initial template
        // ------------------------------------------------------------------
        $append = array( 'user_id' => $data->user->id );
        $data->template->memo = factory( SaleMemo::class )->states('init')->make( $append );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $data->page_title = 'マスター'; // Document title
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Rank tab filter
        // ------------------------------------------------------------------
        $data->tabs = collect([
            array( 'id' => 'all', 'label' => '初期表示' ),
            array( 'id' => 1, 'label' => '精完' ),
            array( 'id' => 2, 'label' => '決完' ),
            array( 'id' => 3, 'label' => '契済' ),
            array( 'id' => 4, 'label' => '予約' ),
            array( 'id' => 5, 'label' => '販止' ),
            array( 'id' => 6, 'label' => '販売' ),
            array( 'id' => 7, 'label' => '準良' ),
            array( 'id' => 8, 'label' => '準造' ),
            array( 'id' => 9, 'label' => '準問' ),
            array( 'id' => 10, 'label' => '未設' ),
            array( 'id' => 11, 'label' => '収益' ),
        ]);
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Pagination settings
        // ------------------------------------------------------------------
        $data->pagination = array(
            'first' => array( 'label' => '最初' ),
            'prev' => array( 'label' => '前' ),
            'next' => array( 'label' => '次' ),
            'last' => array( 'label' => '最後' ),
            'length' => 5
        );
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Organizer list
        // ------------------------------------------------------------------
        $data->organizers = Company::has('sale')->get();
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Preset option data
        // ------------------------------------------------------------------
        $preset = new \stdClass;
        $data->preset = $preset;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Preset periods
        // ------------------------------------------------------------------
        $preset->periods = collect( config( 'const.CONTRACT_PERIODS' ));
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Preset years
        // ------------------------------------------------------------------
        $years = collect(); $preset->years = $years; $today = Carbon::now();
        $oldest = Project::orderBy( 'created_at', 'asc' )->first(); // Oldest project
        // ------------------------------------------------------------------
        if( $oldest ){
            // --------------------------------------------------------------
            $year = Carbon::parse( $oldest->created_at )->year;
            while( $year <= $today->year +1 ){ $years->push( $year ); $year++; }
            // --------------------------------------------------------------
        } else $years->push( $today->year )->push( $today->year +1 );
        // ------------------------------------------------------------------

        $users = User::all();
        foreach($users as $user) {
            $data->user_list[$user->id] = $user;
        }
        
        // ------------------------------------------------------------------
        // dd( $data );
        return view( 'backend.master.sale-list.index', (array) $data );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Handle filter request
    // ----------------------------------------------------------------------
    public function filter( Request $request ){
        // ------------------------------------------------------------------
        $filter = (object) $request->filter;
        $periods = config( 'const.CONTRACT_PERIODS' );
        $response = (object) array( 'status' => 'success' );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Default configuration
        // ------------------------------------------------------------------
        $page = $filter->page ?? 1;
        $perpage = 10; $columns = ['*'];
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Base query
        // ------------------------------------------------------------------
        $relations = collect([ 
            'salememos',
            'salememos.user',
            'sale', 
            'contract',
            'contract.mediations',
            'contract.fee.prices',
            'plan.setting.project',
            'project.purchaseSale.organizer',
            'project.purchaseSale.buyerStaffs.user',
            'project.finance.units',
            'project.finance.units.moneys',
            'project.finance.units.repayments'
        ]);
        // ------------------------------------------------------------------
        $query = Section::orderBy( 'id', 'desc' );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // if there is nothing to filter
        // ------------------------------------------------------------------
        if( empty( $request->filter )){
            $response->result = $query->paginate( $perpage, $columns, 'page', $page );
            return response()->json( $response );
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Project filters
        // ------------------------------------------------------------------
        $query->whereHas( 'project', function( Builder $project ) use( $filter ){
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Minimum project ID
            // --------------------------------------------------------------
            if( !empty( $filter->min )){
                $project->where( 'id', '>=', (int) $filter->min );
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Maximum project ID
            // --------------------------------------------------------------
            if( !empty( $filter->max )){
                $project->where( 'id', '<=', (int) $filter->max );
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Project title
            // --------------------------------------------------------------
            if( !empty( $filter->title )){
                $keywords = explode( ' ', $filter->title );
                if( !empty( $keywords )) $project->where( function( $project ) use( $keywords ){
                    // ------------------------------------------------------
                    foreach( $keywords as $term ){
                        $project->where( 'title', 'like', "%{$term}%" );
                    }
                    // ------------------------------------------------------
                });
            }
            // --------------------------------------------------------------
        });
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Organizer company
        // ------------------------------------------------------------------
        if( !empty( $filter->organizer )){
            $organizerID = (int) $filter->organizer;
            $query->whereHas( 'project.purchaseSale', function( Builder $sale ) use( $organizerID ){
                $sale->where( 'company_id_organizer', $organizerID  );
            });
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Contract payment date
        // ------------------------------------------------------------------
        if( !empty( $filter->payment_year )){
            // --------------------------------------------------------------
            $field = 'contract_payment_date';
            $relation = 'project.purchase.targets.contract';
            // --------------------------------------------------------------
            $relations->push( $relation );
            $query->whereHas( $relation, function( Builder $contract ) use( $filter, $field ){
                // ----------------------------------------------------------
                $year = (int) $filter->payment_year;
                $contract->whereYear( $field, $year );
                // ----------------------------------------------------------
                if( !empty( $filter->payment_month )){
                    $month = (int) $filter->payment_month;
                    $contract->whereMonth( $field, $month );
                }
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Contract payment period
        // ------------------------------------------------------------------
        if( !empty( $filter->payment_period ) && !empty( $periods )){
            // --------------------------------------------------------------
            $relation = 'project.purchase.targets.contract';
            $relations->push( $relation );
            // --------------------------------------------------------------
            $query->whereHas( $relation, function( Builder $contract ) use( $filter, $periods ){
                // ----------------------------------------------------------
                $field = 'contract_payment_date';
                $period = (int) $filter->payment_period;
                $period = (object) $periods[ $period ];
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                if( !empty( $period->period )){
                    // ------------------------------------------------------
                    $range = (object) $period->period;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Starting period
                    // ------------------------------------------------------
                    if( !empty( $range->start )){
                        $start = Carbon::parse( $range->start );
                        $contract->where( $field, '>=', $start );
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Ending period
                    // ------------------------------------------------------
                    if( !empty( $range->end )){
                        $end = Carbon::parse( $range->end );
                        $contract->where( $field, '<=', $end );
                    }
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Sale contract payment date. After [C2] module
        // ------------------------------------------------------------------
        if( !empty( $filter->sale_payment_year )){}
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Sale contract payment period. After [C2] module
        // ------------------------------------------------------------------
        if( !empty( $filter->sale_payment_period )){}
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Contract date
        // ------------------------------------------------------------------
        if( !empty( $filter->contract_year )){
            // --------------------------------------------------------------
            $field = 'contract_date';
            $relation = 'project.purchase.targets.contract';
            // --------------------------------------------------------------
            $relations->push( $relation );
            $query->whereHas( $relation, function( Builder $contract ) use( $filter, $field ){
                // ----------------------------------------------------------
                $year = (int) $filter->contract_year;
                $contract->whereYear( $field, $year );
                // ----------------------------------------------------------
                if( !empty( $filter->contract_month )){
                    $month = (int) $filter->contract_month;
                    $contract->whereMonth( $field, $month );
                }
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Sale contract date. After [C2] module
        // ------------------------------------------------------------------
        if( !empty( $filter->sale_contract_year )){}
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Loan period date. Unexplained
        // ------------------------------------------------------------------
        if( !empty( $filter->loan_year )){
            // --------------------------------------------------------------
            $field = 'loan_period_date';
            $relation = 'project.finance.units';
            // --------------------------------------------------------------
            $relations->push( $relation );
            $query->whereHas( $relation, function( Builder $unit ) use( $filter, $field ){
                // ----------------------------------------------------------
                $year = (int) $filter->loan_year;
                $unit->whereYear( $field, $year );
                // ----------------------------------------------------------
                if( !empty( $filter->loan_month )){
                    $month = (int) $filter->loan_month;
                    $unit->whereMonth( $field, $month );
                }
                // ----------------------------------------------------------
            });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Empty contract date
        // ------------------------------------------------------------------
        if( !empty( $filter->empty_contract )){
            // --------------------------------------------------------------
            $relation = 'project.purchase.targets.contract';
            $relations->push( $relation );
            // --------------------------------------------------------------
            $query->whereHas( $relation, function( Builder $contract ){
                $contract->whereNull( 'contract_date' );
            });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Book price different
        // ------------------------------------------------------------------
        if( !empty( $filter->different_price )){
            // --------------------------------------------------------------
            // $imports = app( ExpenseCtrl::class )->import(1);
            // dd( $imports );
            // --------------------------------------------------------------
            // $relation = 'setting.plans.sections';
            // $relations->push( $relation );
            // --------------------------------------------------------------
            // $query = $query->whereHas( $relation, function( Builder $query ){
            //     $query->whereNull( 'contract_date' );
            // });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Loan status undecided (未)
        // ------------------------------------------------------------------
        if( !empty( $filter->loan_undecided )){
            // --------------------------------------------------------------
            $relation = 'project.finance.units.moneys';
            $relations->push( $relation );
            // --------------------------------------------------------------
            $query = $query->whereHas( $relation, function( Builder $money ){
                $money->where( 'loan_status', 1 );
            });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Loan status expected (予)
        // ------------------------------------------------------------------
        if( !empty( $filter->loan_expected )){
            // --------------------------------------------------------------
            $relation = 'project.finance.units.moneys';
            $relations->push( $relation );
            // --------------------------------------------------------------
            $query = $query->whereHas( $relation, function( Builder $money ){
                $money->where( 'loan_status', 2 );
            });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Loan status confirmed (確)
        // ------------------------------------------------------------------
        if( !empty( $filter->loan_confirmed )){
            // --------------------------------------------------------------
            $relation = 'prject.finance.units.moneys';
            $relations->push( $relation );
            // --------------------------------------------------------------
            $query = $query->whereHas( $relation, function( Builder $money ){
                $money->where( 'loan_status', 3 );
            });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Loan status applied (済)
        // ------------------------------------------------------------------
        if( !empty( $filter->loan_applied )){
            // --------------------------------------------------------------
            $relation = 'project.finance.units.moneys';
            $relations->push( $relation );
            // --------------------------------------------------------------
            $query = $query->whereHas( $relation, function( Builder $money ){
                $money->where( 'loan_status', 4 );
            });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Loan status completed (完)
        // ------------------------------------------------------------------
        if( !empty( $filter->loan_completed )){
            // --------------------------------------------------------------
            $relation = 'project.finance.units.moneys';
            $relations->push( $relation );
            // --------------------------------------------------------------
            $query = $query->whereHas( $relation, function( Builder $money ){
                $money->where( 'loan_status', 5 );
            });
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        

        // ------------------------------------------------------------------
        // Add the relations, make sure they are unique
        // ------------------------------------------------------------------
        $query->with( $relations->unique()->all());
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Paginate the result
        // ------------------------------------------------------------------
        $paginator = $query->paginate( $perpage, $columns, 'page', $page );
        // ------------------------------------------------------------------


        // $collection = $paginator->getCollection();
        // $filteredCollection = $collection->filter( function( $model ){
        //     return $model->id == 5;
        // });
        // $paginator->setCollection( $filteredCollection );

        // ------------------------------------------------------------------
        $response->result = $paginator;
        return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Update request
    // ----------------------------------------------------------------------
    public function update( $entryID, Request $request ){
        // ------------------------------------------------------------------
        $alert = new \stdClass; $response = new \stdClass;
        $data = new \stdClass; $response->data = $data;
        $response->alert = $alert; $response->status = 'error';
        // ------------------------------------------------------------------
        $alert->icon = 'error'; $alert->heading = __('label.error');
        $alert->text = __('label.failed_update_message');
        // ------------------------------------------------------------------
        $updates = (object) $request->updates;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        try {
            // --------------------------------------------------------------
            // Find the inspection entry
            // --------------------------------------------------------------
            // $model = Inspection::find( $entryID );
            // if( !$model ) return response()->json( (array) $response );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Collect update-able fields
            // --------------------------------------------------------------
            // $fields = collect([ 'examination' ]);
            // if( 3 === $model->kind ) $fields->push( 'examination_text' );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Assign the updates
            // --------------------------------------------------------------
            // $fields = $fields->unique()->values(); // Remove duplicates
            // $fields->each( function( $field ) use( $model, $updates ){
            //     if( property_exists( $updates, $field )) $model->{$field} = $updates->{$field};
            // });
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // $model->save(); // Save the updates
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // $response->status = 'success'; // Update the alert
            // $alert->icon = 'success'; $alert->heading = __('label.success');
            // $alert->text = __('label.success_update_message');
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // $response->updates = $request->updates;
            return response()->json( (array) $response );
            // --------------------------------------------------------------
        } catch( \Exception $error ){
            // --------------------------------------------------------------
            // error response
            // --------------------------------------------------------------
            Log::error([
                'message' => $error->getMessage(),
                'file'    => $error->getFile().'         : '.$error->getLine(),
                'route'   => $_SERVER['REQUEST_METHOD'].': '.$_SERVER['REQUEST_URI'],
            ]);
            // --------------------------------------------------------------
            $alert->error = $error->getMessage();
            return response()->json( $response , 500 );
            // --------------------------------------------------------------
        }
    }
    // ----------------------------------------------------------------------
}