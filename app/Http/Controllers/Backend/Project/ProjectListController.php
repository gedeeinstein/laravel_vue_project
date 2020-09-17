<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Project;
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
// --------------------------------------------------------------------------
use App\Models\PjMemo;
use App\Models\Project;
use App\Models\PjBasicQA;
use App\Models\PjAdditionalQa;
use App\Models\OtherAdditionalQaCheck;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class ProjectListController extends Controller {
    // ----------------------------------------------------------------------
    public function index(){
        $data = new \stdClass;
        $data->user = Auth::user();
        $data->filters = new \stdClass;
        $data->template = new \stdClass;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Create memo initial template
        // ------------------------------------------------------------------
        $append = array( 'user_id' => $data->user->id );
        $data->template->memo = factory( PjMemo::class )->states('init')->make( $append );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $data->page_title = '仕入一覧';
        $data->tabs = collect([
            array( 'id' => 'all', 'label' => '初期表示', 'total' => null ),
            array( 'id' => 1, 'label' => '登', 'color' => 'red', 'total' => null ),
            array( 'id' => 2, 'label' => '決', 'color' => 'red', 'total' => null ),
            array( 'id' => 3, 'label' => '済', 'color' => 'skyblue', 'total' => null ),
            array( 'id' => 4, 'label' => '確', 'color' => 'blue', 'total' => null ),
            array( 'id' => 5, 'label' => '買付', 'color' => 'green', 'total' => null ),
            array( 'id' => 6, 'label' => '情', 'color' => 'magenta', 'total' => null ),
            array( 'id' => 7, 'label' => '保', 'total' => null ),
            array( 'id' => 8, 'label' => 'OUT', 'total' => null ),
        ]);
        // ------------------------------------------------------------------
        $data->tabs = $data->tabs->map( function( $tab ){
            $tab = (object) $tab;
            // --------------------------------------------------------------
            $tab->total = Project::whereHas( 'purchaseSale', function( $query ) use( $tab ){
                if( 'all' !== $tab->id ) $query->where( 'project_status', (int) $tab->id );
            })->count();
            // --------------------------------------------------------------
            return $tab;
            // --------------------------------------------------------------
        });
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
        // Compose filters
        // ------------------------------------------------------------------
        $filter = new \stdClass;
        $filter->year = collect();
        $paymentDates = collect(); $contractDates = collect();
        // ------------------------------------------------------------------
        $projects = Project::select('id')->with([ 
            'purchase' => function( $query ){
                $query->select( 'id', 'project_id' );
            },
            'purchase.targets' => function( $query ){
                $query->select( 'id', 'pj_purchase_id' );
            },
            'purchase.targets.contract' => function( $query ){
                $query->select( 'id', 'pj_purchase_target_id', 'contract_date', 'contract_payment_date' );
            }
        ])->get();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // List all contract and payment dates
        // ------------------------------------------------------------------
        $projects->each( function( $project ) use( $contractDates, $paymentDates ){
            if( !empty( $project->purchase->targets )){
                $project->purchase->targets->each( function( $target ) use( $contractDates, $paymentDates ){
                    if( !empty( $target->contract->contract_date )){
                        $date = Carbon::parse( $target->contract->contract_date );
                        $contractDates->push( $date );
                    }
                    if( !empty( $target->contract->contract_payment_date )){
                        $date = Carbon::parse( $target->contract->contract_date );
                        $paymentDates->push( $date );
                    }
                });
            }
        });
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Find the oldest date
        // ------------------------------------------------------------------
        $oldest = null; 
        foreach( $paymentDates as $date ){
            if( !$oldest || $oldest->greaterThan( $date )) $oldest = $date;
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Build the year filter
        // ------------------------------------------------------------------
        if( !$oldest ) $filter->year->push( Carbon::now()->year );
        else {
            $current = $oldest; $now = Carbon::now();
            while( true ){
                $filter->year->push( $current->year );
                if( $current->greaterThan( $now )) break;
                $current = $current->addYear();
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Period filter
        // ------------------------------------------------------------------
        $period = collect(); $filter->period = $period;
        $period->push([ 'id' => 1, 'label' => '8期売上(~18.7末)']);
        $period->push([ 'id' => 2, 'label' => '9期売上(~19.7末)']);
        $period->push([ 'id' => 3, 'label' => '10期売上(~20.7末)']);
        $period->push([ 'id' => 4, 'label' => '10期売上(20.8~)']);
        $period->push([ 'id' => 5, 'label' => '現期+前期1期+未来2期以降']);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $data->filter = $filter;
        return view( 'backend.project.list.index', (array) $data );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Handle filter request
    // ----------------------------------------------------------------------
    public function filter( Request $request ){
        $response = (object) array( 'status' => 'success' );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Purchase sale relations
        // ------------------------------------------------------------------
        $sale = 'purchaseSale';
        $relations = collect([ $sale, "{$sale}.buyerStaffs", "{$sale}.buyerStaffs.user" ]);
        // ------------------------------------------------------------------
        $relations = $relations->concat([ 'memos', 'memos.user' ]);
        $relations = $relations->concat([ "{$sale}.organizer", "{$sale}.explainer" ]);
        $relations = $relations->concat([ 
            'purchase', 'purchase.targets', 
            'purchase.targets.doc', 'purchase.targets.contract', 'purchase.targets.buildings'
        ]);
        $relations = $relations->concat([ 
            'property', 'property.residentials', 'property.residentials.residentialB'
        ]);
        // ------------------------------------------------------------------
        $query = Project::select('*'); // Project query
        // ------------------------------------------------------------------
        // dd( $query->get()->toJson());
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Process filters
        // ------------------------------------------------------------------
        if( !empty( $request->filter )){
            $filter = (object) $request->filter;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Swap min and max ID filter if the value is reversed
            // --------------------------------------------------------------
            if( !empty( $filter->min ) && !empty( $filter->max )){
                if( $filter->min > $filter->max ){
                    $min = $filter->min; $max = $filter->max;
                    $filter->min = $max; $filter->max = $min;
                }
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Default configuration
            // --------------------------------------------------------------
            $page = $filter->page ?? 1;
            $perpage = 10; $columns = ['*'];
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Empty contract date query extender
            // --------------------------------------------------------------
            $emptyContractDate = function( $query ){
                return $query->where( function( $query ){
                    $query->whereHas( 'purchase.targets.contract', function( $query ){
                        $query->whereNull( 'contract_date' );
                    })->orWhereDoesntHave( 'purchase.targets.contract' );
                });
            };
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Status
            // --------------------------------------------------------------
            if( !empty( $filter->status )){
                $status = $filter->status;
                if( 'all' !== $status ){
                    $query = $query->whereHas('purchaseSale', function( $query ) use( $status ){
                         $query->where( 'project_status', (int) $status );
                    });
                }
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Minimum ID
            // --------------------------------------------------------------
            if( !empty( $filter->min )){
                $query = $query->where( 'id', '>=', (int) $filter->min );
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Maximum ID
            // --------------------------------------------------------------
            if( !empty( $filter->max )){
                $query = $query->where( 'id', '<=', (int) $filter->max );
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Search title
            // --------------------------------------------------------------
            if( !empty( $filter->title )){
                $keywords = explode( ' ', trim( $filter->title ));
                if( !empty( $keywords )) foreach( $keywords as $keyword ){
                    $query = $query->where( 'title', 'LIKE', "%{$keyword}%" );
                }
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Payment year
            // --------------------------------------------------------------
            if( !empty( $filter->year )){
                $year = $filter->year;
                $empty = $filter->empty ?? false; 
                // ----------------------------------------------------------
                if( 'all' !== $year ){
                    $query = $query->whereHas( 'purchase.targets.contract', function( $query ) use( $year, $empty, $emptyContractDate ){
                        $query->whereYear( 'contract_payment_date', (int) $year );
                    });
                    if( $empty ) $query = $emptyContractDate( $query );
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Payment month
            // --------------------------------------------------------------
            if( !empty( $filter->month )){
                $month = $filter->month;
                $empty = $filter->empty ?? false; 
                // ----------------------------------------------------------
                if( 'all' !== $month ){
                    $query = $query->whereHas( 'purchase.targets.contract', function( $query ) use( $month, $empty, $emptyContractDate ){
                        $query->whereMonth( 'contract_payment_date', (int) $month );
                    });
                    if( $empty ) $query = $emptyContractDate( $query );
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Period filter
            // Reference: https://bit.ly/2Y6i0OL
            // --------------------------------------------------------------
            if( !empty( $filter->period )){
                $period = $filter->period;
                $empty = $filter->empty ?? false; 
                // ----------------------------------------------------------
                if( 'all' !== $period ){
                    $period = (int) $period;
                    $query = $query->whereHas( 'purchase.targets.contract', function( $query ) use( $period ){
                        // --------------------------------------------------
                        if( 1 === $period ){ // 8期売上(~18.7末)
                            $preset = Carbon::parse('2018-07-31');
                            $query->whereDate( 'contract_payment_date', '<=', $preset );
                        }
                        // --------------------------------------------------
                        elseif( 2 === $period ){ // 9期売上(~19.7末)
                            $preset = Carbon::parse('2019-07-31');
                            $query->whereDate( 'contract_payment_date', '<=', $preset );
                        }
                        // --------------------------------------------------
                        elseif( 3 === $period ){ // 10期売上(~20.7末) 
                            $preset = Carbon::parse('2020-07-31');
                            $query->whereDate( 'contract_payment_date', '<=', $preset );
                        }
                        // --------------------------------------------------
                        elseif( 4 === $period ){ // 10期売上(20.8~) 
                            $preset = Carbon::parse('2019-08-01');
                            $query->whereDate( 'contract_payment_date', '>=', $preset );
                        }
                        // --------------------------------------------------
                        elseif( 5 === $period ){ // 現期+前期1期+未来2期以降
                            $preset = Carbon::parse('2019-08-01');
                            $query->whereDate( 'contract_payment_date', '>=', $preset );
                        }
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------
                    if( $empty ) $query = $emptyContractDate( $query );
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Empty contract date
            // --------------------------------------------------------------
            if( !empty( $filter->empty )) $query = $emptyContractDate( $query );
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Paginate the result
            // --------------------------------------------------------------
            $query = $query->with( $relations->all());
            $response->result = $query->paginate( $perpage, $columns, 'page', $page );
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // dd( $response );
        return response()->json( $response );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Create new project
    // ----------------------------------------------------------------------
    public function create( Request $request ){
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'success';
        // ------------------------------------------------------------------
        $alert = new \stdClass;
        $alert->heading = __('label.success');
        $alert->text = __('label.success_create_message');
        $response->alert = $alert;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Create the new project
        // ------------------------------------------------------------------
        if( !empty( $request->entry )){
            $entry = (object) $request->entry;
            if( !empty( $entry->title )){
                // ----------------------------------------------------------
                $project = factory( Project::class )->states('init')->make();
                $project->title = $entry->title;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                $now = Carbon::now();
                $query = Project::whereYear( 'created_at', $now->year );
                $groupCount = $query->whereMonth( 'created_at', $now->month )->count();
                // ----------------------------------------------------------
                $serial = sprintf("%02d", $groupCount +1 );
                $group = "{$now->format('y')}{$now->format('m')}";
                $project->port_pj_info_number = "J{$group}-{$serial}";
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                $project->created_at = Carbon::now();
                $project->updated_at = Carbon::now();
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                $project->save(); // Save as new entry
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                if( !$project->id ) {
                    $response->status = 'error';
                    $alert->heading = __('label.error');
                    $alert->text = __('label.failed_create_message');
                }
                // ----------------------------------------------------------
                else {
                    // ------------------------------------------------------
                    // Return the project ID to display it in the list
                    // ------------------------------------------------------
                    $response->id = $project->id;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Create basic questions
                    // ------------------------------------------------------
                    $append = array( 'project_id' => $project->id );
                    factory( PjBasicQA::class )->states('init')->create( $append );
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Create addition question answers
                    // ------------------------------------------------------
                    $questions = OtherAdditionalQaCheck::get();
                    $questions->each( function( $question ) use( $project ){
                        // --------------------------------------------------
                        $append = array( 'project_id' => $project->id, 'question_id' => $question->id );
                        factory( PjAdditionalQa::class )->states('init')->create( $append );
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json( $response );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Delete project
    // ----------------------------------------------------------------------
    public function delete( $project, Request $request ){
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'error';
        // ------------------------------------------------------------------
        $alert = new \stdClass;
        $alert->icon = 'error';
        $alert->heading = __('label.error');
        $alert->text = __('label.failed_delete_message');
        $response->alert = $alert;
        // ------------------------------------------------------------------
        if( empty( $project )) response()->json( $response );
        $projectID = (int) $project;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        try {
            // --------------------------------------------------------------
            if( $projectID ){
                // ----------------------------------------------------------
                // Find and delete the project
                // ----------------------------------------------------------
                $project = Project::find( $projectID );
                if( !$project ) return response()->json( $response );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                $today = Carbon::now();
                $created = Carbon::parse( $project->created_at ); // Get the project created date
                $serial = explode( '-', $project->port_pj_info_number );
                $baseNumber = $serial[0];
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                $project->delete(); // Delete the project
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Reorder port-number
                // https://www.chatwork.com/#!rid170074178-1314453633571487744
                // ----------------------------------------------------------
                $difference = $created->diffInMonths( $today );
                if( $difference <= 1 ){
                    // ------------------------------------------------------
                    $query = Project::where( 'port_pj_info_number', 'LIKE', "%{$baseNumber}%" );
                    $projects = $query->orderBy( 'created_at', 'asc' )->get();
                    // ------------------------------------------------------
                    if( !$projects->isEmpty()) $projects->each( function( $project, $index ) use( $baseNumber ){
                        $counter = sprintf( "%02d", $index +1 );
                        // --------------------------------------------------
                        $project->port_pj_info_number = "{$baseNumber}-{$counter}";
                        $project->save();
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                if( $project->trashed()){
                    $response->status = 'success';
                    // ------------------------------------------------------
                    $alert->icon = 'success';
                    $alert->heading = __('label.success');
                    $alert->text = __('label.success_delete_message');
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            return response()->json( $response );
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        catch( \Exception $error ){
            // --------------------------------------------------------------
            // Error logging
            // --------------------------------------------------------------
            Log::error([
                'message'   => $error->getMessage(),
                'file'      => $error->getFile().':'.$error->getLine(),
                'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
            ]);
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $response->error = $error->getMessage();
            return response()->json( $response, 500 );
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
