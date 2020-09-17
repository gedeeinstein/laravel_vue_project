<?php
// -----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Project;
// -----------------------------------------------------------------------------
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
// -----------------------------------------------------------------------------
use App\Traits\LogActivityTrait;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
// -----------------------------------------------------------------------------
use App\Models\MasterValue;
use App\Models\MasterRegion;
// -----------------------------------------------------------------------------
use App\Models\User;
use App\Models\Company;
use App\Models\StatCheck;
// -----------------------------------------------------------------------------
use App\Models\Project;
use App\Models\PjProperty;
use App\Models\PjPropertyOwner;
// -----------------------------------------------------------------------------
use App\Models\PjLotResidentialA;
use App\Models\PjLotRoadA;
use App\Models\PjLotBuildingA;
// -----------------------------------------------------------------------------
use App\Models\PjLotResidentialPurchase;
use App\Models\PjLotRoadPurchase;
// -----------------------------------------------------------------------------
use App\Models\PjPurchaseSale;
use App\Models\PjPurchase;
use App\Models\PjPurchaseTarget;
// -----------------------------------------------------------------------------
use App\Models\PjMemo;
use App\Models\PjPurchaseSalePjMemo;
use App\Models\PjPurchaseSaleBuyerStaff;
// -----------------------------------------------------------------------------

class ProjectPurchaseSalesController extends Controller
{
	// -------------------------------------------------------------------------
	use LogActivityTrait;
	// -------------------------------------------------------------------------

	// Model
	// -------------------------------------------------------------------------
	public function __construct(){
		// ---------------------------------------------------------------------
		$this->user 						= new User();
		$this->company 						= new Company();
		$this->stat_check 					= new StatCheck();
		// ---------------------------------------------------------------------
		$this->project 						= new Project();
		$this->property 					= new PjProperty();
		$this->property_owners 				= new PjPropertyOwner();
		// ---------------------------------------------------------------------
		$this->residentials_a 				= new PjLotResidentialA();
		$this->roads_a 						= new PjLotRoadA();
		$this->buildings_a 					= new PjLotBuildingA();
		// ---------------------------------------------------------------------
		$this->residential_purchase 		= new PjLotResidentialPurchase();
		$this->road_purchase 				= new PjLotRoadPurchase();
		// ---------------------------------------------------------------------
		$this->purchaseSales 				= new PjPurchaseSale();
		$this->purchase 					= new PjPurchase();
		$this->purchase_target 				= new PjPurchaseTarget();
		// ---------------------------------------------------------------------
		$this->purchaseSaleBuyerStaff 		= new PjPurchaseSaleBuyerStaff();
		$this->purchaseSaleBuyerStaffMemo 	= new PjPurchaseSalePjMemo();
		// ---------------------------------------------------------------------
	}
	// -------------------------------------------------------------------------

	// Validation Rule
	// -------------------------------------------------------------------------
	protected function validator(array $data){
		return Validator::make($data, [
			'entry.organizer_realestate_explainer'				=> 'required',
			'entry.offer_route'                     			=> 'required',
			'entry.offer_date'                      			=> 'required',
			'entry.project_type'                    			=> 'required',
			'entry.project_urbanization_area'       			=> 'required',
			'entry.project_address'                 			=> 'max:128',
			'entry.project_address_extra'           			=> 'max:128',
			'roads.*.road_purchase.urbanization_area'			=> 'required',
			'roads.*.road_purchase.urbanization_area_number'	=> 'max:128',
			'PJMemos.*.content'          						=> 'max:128',
			'stat_check.memo'        							=> 'max:128',
		]);
	}
	// -------------------------------------------------------------------------

	public function create($projectId){
		$data = $this->source_data($projectId);
		return view('backend.project.purchases-sales.form', (array) $data);
	}

	public function update($projectId, Request $request){
		// validate request
		$this->validator($request->all())->validate();

		try{
            // -----------------------------------------------------------------
            $user = Auth::user();
            // -----------------------------------------------------------------

            // -----------------------------------------------------------------
			// STATUS CHECK
			// -----------------------------------------------------------------
			// create or update stat_check
			$data_status 				= $request->stat_check;
			$data_status['project_id'] 	= $projectId;
			$stat_check = $this->stat_check->updateOrCreate([
					'project_id' => $projectId,
					'screen'     => 'pj_purchase_sales'
			], $data_status );
			// -----------------------------------------------------------------
			// check status -> if db not changes and status is 1 prevent saving data
			$status_changed = $stat_check->getChanges();
			$status_created = $stat_check->wasRecentlyCreated;
			if (!isset($status_changed['status']) && $stat_check->status == 1 && !$status_created) {
					return response()->json([
							'status'  => 'warning',
							'message' => '「完」の場合編集できません。'
					]);
			}
			// -----------------------------------------------------------------

			// -----------------------------------------------------------------
			// save and update process
			// -----------------------------------------------------------------
			$project = $this->project::find($projectId);

			if(!isset($request)){
				return response()->json(null);
			}
			// grab request entry for purchase sale table.
			$dataPurchaseSale = $request->entry;
			$dataPurchaseSale['project_id'] = $projectId;

			//find the id
			$projectPurchaseSale = $this->purchaseSales::find($dataPurchaseSale['id']);

			// save or update purchase sale
			// -----------------------------------------------------------------
			if(isset($projectPurchaseSale->id)){
				$this->saveLog('Update Purchase Sales on Project: ', $project->title, Auth::user()->email, Auth::user()->id);
				$projectPurchaseSale->update($dataPurchaseSale);
				$newProjectPurchaseSaleId = $projectPurchaseSale->id;
			}
			else{
				$this->saveLog('Create Purchase Sales on Project: ', $project->title, Auth::user()->email, Auth::user()->id);
				$projectPurchaseSale = $this->purchaseSales::firstOrCreate([
					'id' => $dataPurchaseSale['id'],
				], $dataPurchaseSale);
				$newProjectPurchaseSaleId = $projectPurchaseSale->id;
			}
			// -----------------------------------------------------------------

			// save or update purchase sale buyer staff
			// -----------------------------------------------------------------
			foreach($request->buyerStaff as $buyer){
				$buyer['pj_purchase_sale_id'] = $newProjectPurchaseSaleId;
				$this->purchaseSaleBuyerStaff::updateOrCreate([
					'id'                  => $buyer['id'],
					'pj_purchase_sale_id' => $newProjectPurchaseSaleId,
				], $buyer);
			}
			// -----------------------------------------------------------------

			// save or update purchase sale memo
			// -----------------------------------------------------------------
			foreach($request->PJMemos as $memo){
                // -------------------------------------------------------------
                // Save purchase sale memo
                // -------------------------------------------------------------
				$memo['pj_purchase_sale_id'] = $newProjectPurchaseSaleId;
				$record = $this->purchaseSaleBuyerStaffMemo::withTrashed()->updateOrCreate([
					'id'                  => $memo['id'],
					'pj_purchase_sale_id' => $newProjectPurchaseSaleId,
                ], $memo);
                // -------------------------------------------------------------

                // -------------------------------------------------------------
                $record->load('memo'); // Load relation to project memo
                // -------------------------------------------------------------

                // -------------------------------------------------------------
                // Get project memo, if unavailable create new one
                // -------------------------------------------------------------
                $projectMemo = $record->memo;
                if( !$projectMemo ) $projectMemo = new PjMemo();
                // -------------------------------------------------------------

                // -------------------------------------------------------------
                // Update project memo properties
                // -------------------------------------------------------------
                $projectMemo->project_id = $project->id;
                $projectMemo->user_id = $user->id;
                $projectMemo->content = $memo['content'];
                // -------------------------------------------------------------

                // -------------------------------------------------------------
                // If sale memo is soft-deleted, disable the project memo
                // -------------------------------------------------------------
                if( $record->trashed()) $projectMemo->disabled = true;
                // -------------------------------------------------------------

                // -------------------------------------------------------------
                $projectMemo->save(); // Save the project memo
                // -------------------------------------------------------------

                // -------------------------------------------------------------
                // Update foreign ID of sale memo
                // -------------------------------------------------------------
                $record->pj_memo_id = $projectMemo->id;
                $record->save();
                // -------------------------------------------------------------
			}
			// -----------------------------------------------------------------

			// save or update residential purchase
			// -----------------------------------------------------------------
			if (count($request->residentials) > 0) {
				foreach ($request->residentials as $key => $residential) {
					$residential['residential_purchase']['pj_lot_residential_a_id'] = $residential['id'];
					$this->residential_purchase::updateOrCreate([
						'id' => $residential['residential_purchase']['id'],
						'pj_lot_residential_a_id' => $residential['residential_purchase']['pj_lot_residential_a_id'],
					], $residential['residential_purchase']);
				}
			}
			// -----------------------------------------------------------------

			// save or update road purchase
			// -----------------------------------------------------------------
			if (count($request->roads) > 0) {
				foreach ($request->roads as $key => $road) {
					$road['road_purchase']['pj_lot_road_a_id'] = $road['id'];
					$this->road_purchase::updateOrCreate([
						'id' => $road['road_purchase']['id'],
						'pj_lot_road_a_id' => $road['road_purchase']['pj_lot_road_a_id'],
					], $road['road_purchase']);
				}
			}
			// -----------------------------------------------------------------

			// -----------------------------------------------------------------
			// if purchase_sale.project_status (A61-21) <= 3
			// crete port_contract_number
			// -----------------------------------------------------------------
			$purchase = $this->purchase::where('project_id', $projectId)->first();
			$purchase_targets = $this->purchase_target::with('purchase_contract')->where('pj_purchase_id', $purchase->id ?? null)->get();

			// create port_contract_number if purchase and purchase target already creted
			if ($request->entry['project_status'] <= 3 && $purchase && count($purchase_targets) > 0) {
	            $project = $this->project::find($projectId);
				// get purchase contract data
				// -------------------------------------------------------------
				$purchase_contracts = $purchase_targets->map(function ($item, $key) {
				    return $item->purchase_contract;
				});
				// -------------------------------------------------------------

				// if purchase contract found and port_contract_number haven't created before
				// get latest contract date
				// -------------------------------------------------------------
				if (count($purchase_contracts) > 0 && $purchase_contracts[0] && !$project->port_contract_number) {
					$contract_dates = $purchase_contracts->mapWithKeys(function ($item) {
					    return [$item['contract_date']];
					});
					$latest_contract_date = Carbon::parse($contract_dates->max());

					// get project with same year and month as latest contract date
					// ---------------------------------------------------------
					$projects = $this->project::where('port_contract_number', 'LIKE', "{$latest_contract_date->format('y')}{$latest_contract_date->format('m')}")
						->whereNotIn('id', [$project->id])
						->withTrashed()
						->get();
					// ---------------------------------------------------------

					// if project found
					// continue the serial number
					// else make serial number as 1
					// ---------------------------------------------------------
					if (count($projects) > 0) {
						$serial = count($projects) + 1;
						$project->port_contract_number = "{$latest_contract_date->format('y')}{$latest_contract_date->format('m')}-{$serial}";
						$project->save();
					}else {
						$project->port_contract_number = "{$latest_contract_date->format('y')}{$latest_contract_date->format('m')}-1";
						$project->save();
					}
					// ---------------------------------------------------------
				}
				// -------------------------------------------------------------
			}
			// -----------------------------------------------------------------

			// -----------------------------------------------------------------
			// assign response data
			// -----------------------------------------------------------------
			$responseData = $this->source_data($projectId);
			// -----------------------------------------------------------------

			// -----------------------------------------------------------------
			// success response
			// -----------------------------------------------------------------
			return response()->json([
				'status'  => 'success',
				'message' => __('label.success_update_message'),
				'data'    => $responseData,
			]);
			// -----------------------------------------------------------------
		}
		catch(\Exception $error){

			// error response
			// -----------------------------------------------------------------
			Log::error([
					'message'   => $error->getMessage(),
					'file'      => $error->getFile().':'.$error->getLine(),
					'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
			]);
			return response()->json([
				'status'  => 'error',
				'message' => __('label.failed_update_message'),
				'error'   => $error->getMessage(),
			], 500);
			// -----------------------------------------------------------------
		}
	}

	public function delete($project_id, $type, Request $request){
		// ---------------------------------------------------------------------
		$response_data = new \stdClass;
		// ---------------------------------------------------------------------

		try {
			switch( $type ){
                case 'buyerStaff':
                    // ---------------------------------------------------------
					$buyerStaff = $this->purchaseSaleBuyerStaff::find( $request->id );
					$buyerStaff->delete();
                    break;
                    // ---------------------------------------------------------
                case 'PJMemos':
                    // ---------------------------------------------------------
                    // Soft delete sale memo
                    // ---------------------------------------------------------
                    $saleMemo = $this->purchaseSaleBuyerStaffMemo::with('memo')->find( $request->id );
                    if( $saleMemo->memo ){
                        // -----------------------------------------------------
                        // Disable project memo when sale memo is soft-deleted
                        // -----------------------------------------------------
                        $projectMemo = $saleMemo->memo;
                        $projectMemo->disabled = true;
                        $projectMemo->save();
                        // -----------------------------------------------------
                    }
                    // ---------------------------------------------------------
                    $saleMemo->delete();
                    // ---------------------------------------------------------

                    // ---------------------------------------------------------
                    // set response data
                    // ---------------------------------------------------------
					$purchaseSale = $this->purchaseSales::where('project_id', $project_id)->first();
                    $response_data->PJMemos = $this->purchaseSaleBuyerStaffMemo::withTrashed()->where('pj_purchase_sale_id', $purchaseSale['id'])->get();
                    break;
                    // ---------------------------------------------------------
				default:
					break;
			}

			// return succes message
			// -----------------------------------------------------------------
			return response()->json([
				'status'  => 'success',
				'message' => __('label.success_delete_message'),
				'data'		=> $response_data,
			]);
			// -----------------------------------------------------------------
		}
		catch(\Exception $error){

			// return error message
			// -----------------------------------------------------------------
			return response()->json([
				'status'  => 'error',
				'message' => __('label.failed_delete_message'),
				'error'   => $error->getMessage(),
			], 500);
			// -----------------------------------------------------------------
		}
	}

	public function source_data($id)
	{
		// ---------------------------------------------------------------------
		// get data for purchase sale
		// ---------------------------------------------------------------------

		// factory state
		// ---------------------------------------------------------------------
		$initial = collect([
			'purchase_sale' 		=> factory(PjPurchaseSale::class)->states('init')->make(),
			'property' 				=> factory(PjProperty::class)->states('init')->make(),
			'residential_purchase' 	=> factory(PjLotResidentialPurchase::class)->states('init')->make(),
			'road_purchase' 		=> factory(PjLotRoadPurchase::class)->states('init')->make(),
		]);		
		// ---------------------------------------------------------------------

		// get login user role
		// ---------------------------------------------------------------------
		$user_role = auth()->user()->user_role->name;
		// ---------------------------------------------------------------------

		// get project and purchase sale
		// ---------------------------------------------------------------------
		$project 		= $this->project::findOrFail($id);
		$purchaseSale 	= $this->purchaseSales::where('project_id', $id)->first();
		// ---------------------------------------------------------------------

		// set project title and route
		// ---------------------------------------------------------------------
		$page_title 	= __('project_purchases_sales.purchases_sales_text') . '::' . $project->title;
		$form_action 	= route('project.purchases-sales.update', $id);
		// ---------------------------------------------------------------------
		$project_sheet_url 	= route('project.sheet', $id);
		$delete_buyer_staff = route('project.purchases-sales.delete', [
			'project' => $id,
			'type'    => 'buyerStaff',
		]);
		$delete_memo = route('project.purchases-sales.delete', [
			'project' => $id,
			'type'    => 'PJMemos',
		]);
		// ---------------------------------------------------------------------

		// get in house and real estate company
		// ---------------------------------------------------------------------
		$companyKindInHouse = $this->company::where('kind_in_house', 1)->get();
		$companyRealEstate 	= $this->company::select('id', 'name')->where('kind_real_estate_agent', 1)->get();
		// ---------------------------------------------------------------------

		// kind in house company user
		// ---------------------------------------------------------------------
		$userNotaryRegistration = [];
		$buyerStaffWithUserId 	= $companyKindInHouse->load('users');
		// ---------------------------------------------------------------------

		// get project stock procurement
		// ---------------------------------------------------------------------
		$project_sheet 		= $project->sheets->where('is_reflecting_in_budget', 1)->first();
		$stock_procurement 	= $project_sheet ? $project_sheet->stock->procurements : null;
		// ---------------------------------------------------------------------

		// get property and property owner
		// ---------------------------------------------------------------------
		$property 			= $this->property::where('project_id', $id)->with('owners')->first();
		$property_owners 	= $this->property_owners::pluck('name', 'id');
		// ---------------------------------------------------------------------

		// get residential, road and building data with its relation
		// ---------------------------------------------------------------------
		$relations = array(
			'use_districts','build_ratios','floor_ratios'
		);
		// ---------------------------------------------------------------------
		$residential_relations = Arr::flatten(Arr::prepend($relations,
								 ['residential_owners', 'residential_purchase'])
		);
		$residentials = $this->residentials_a::where('pj_property_id', $property->id ?? null)
											 ->with($residential_relations)->get();
		// ---------------------------------------------------------------------
		$road_relations = $relations = Arr::flatten(Arr::prepend($relations,
									   ['road_owners', 'road_purchase'])
		);
		$roads = $this->roads_a::where('pj_property_id', $property->id ?? null)
							   ->with($road_relations)->get();
		// ---------------------------------------------------------------------
		$buildings = $this->buildings_a::where('pj_property_id', $property->id ?? null)->with(
				'building_floors',
				'building_owners')->get();
		// ---------------------------------------------------------------------

		// get stat check
		// ---------------------------------------------------------------------
		$stat_check = $this->stat_check::where('project_id', $project->id)->where('screen', 'pj_purchase_sales')->first();
		// ---------------------------------------------------------------------

		// assign data
		// ---------------------------------------------------------------------
		$data = new \stdClass();
		// ---------------------------------------------------------------------
		$data->initial 			= $initial;
		$data->master_values 	= MasterValue::pluck('value', 'id');
		$data->master_regions 	= MasterRegion::pluck('name', 'id');
		// ---------------------------------------------------------------------
		$data->users 			= $this->user::all();
		$data->editable 		= $user_role == 'accounting_firm' ? false : true;
		$data->radio_editable 	= $user_role == 'global_admin' || 'registration_manager' ? true : false;
		// ---------------------------------------------------------------------
		$data->page_title 			= $page_title;
		$data->form_action 			= $form_action;
		$data->project_sheet_url 	= $project_sheet_url;
		$data->delete_buyer_staff 	= $delete_buyer_staff;
		$data->delete_memo 			= $delete_memo;
		// ---------------------------------------------------------------------
		$data->project 			= $project;
		$data->purchaseSale 	= $purchaseSale;
		// ---------------------------------------------------------------------
		$data->property 		= $property;
		$data->property_owners 	= $property_owners;
		// ---------------------------------------------------------------------
		$data->residentials 	= $residentials;
		$data->roads 			= $roads;
		$data->buildings 		= $buildings;
		// ---------------------------------------------------------------------
		$data->kindinhouse 		= $companyKindInHouse;
		$data->kindrealestate 	= $companyRealEstate;
		// ---------------------------------------------------------------------
		$data->organizerrealestate 	= $userNotaryRegistration;
		$data->buyerStaffWithUserId = $buyerStaffWithUserId;
		// ---------------------------------------------------------------------
		$data->purchaseSaleBuyerStaff 		= $this->purchaseSaleBuyerStaff::where('pj_purchase_sale_id', $purchaseSale['id'])->get();
		$data->purchaseSaleBuyerStaffMemo 	= $this->purchaseSaleBuyerStaffMemo::withTrashed()->where('pj_purchase_sale_id', $purchaseSale['id'])->get();
		// ---------------------------------------------------------------------
		$data->buyerStaff 	= $this->purchaseSaleBuyerStaff::where('pj_purchase_sale_id', $purchaseSale['id'])->get();
		$data->PJMemos 		= $this->purchaseSaleBuyerStaffMemo::withTrashed()->where('pj_purchase_sale_id', $purchaseSale['id'])->get();
		// ---------------------------------------------------------------------
		$data->stock_procurement 	= $stock_procurement;
		// ---------------------------------------------------------------------
		$data->stat_check			= $stat_check;
		// ---------------------------------------------------------------------

		// change date format
		// ---------------------------------------------------------------------
		$data = $this->date_format($data);
		// ---------------------------------------------------------------------

		// ---------------------------------------------------------------------
		return $data;
		// ---------------------------------------------------------------------
	}

	public function date_format($data)
	{
		$format = 'Y/m/d'; // date format

		// change purchase sale date format
		// ---------------------------------------------------------------------
		if ($data->purchaseSale) {
			$data->purchaseSale->project_urbanization_area_date = $data->purchaseSale->project_urbanization_area_date ?
				Carbon::parse($data->purchaseSale->project_urbanization_area_date)->format($format) : null;
			$data->purchaseSale->offer_date = $data->purchaseSale->offer_date ?
				Carbon::parse($data->purchaseSale->offer_date)->format($format) : null;
		}
		// ---------------------------------------------------------------------

		// change residential purchase date format
		// ---------------------------------------------------------------------
		foreach ($data->residentials as $key => $residential) {
			if ($residential->residential_purchase) {
				$residential->residential_purchase->urbanization_area_date = $residential->residential_purchase->urbanization_area_date ?
					Carbon::parse($residential->residential_purchase->urbanization_area_date)->format($format) : null;
			}
		}
		// ---------------------------------------------------------------------

		// change road purchase date format
		// ---------------------------------------------------------------------
		foreach ($data->roads as $key => $road) {
			if ($road->road_purchase) {
				$road->road_purchase->urbanization_area_date = $road->road_purchase->urbanization_area_date ?
					Carbon::parse($road->road_purchase->urbanization_area_date)->format($format) : null;
			}
		}
		// ---------------------------------------------------------------------

		// ---------------------------------------------------------------------
		return $data;
		// ---------------------------------------------------------------------
	}
}
