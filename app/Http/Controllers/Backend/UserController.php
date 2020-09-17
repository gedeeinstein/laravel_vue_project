<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Backend;
// --------------------------------------------------------------------------
use App\Helpers\DatatablesHelper;
use App\Http\Controllers\Controller;
// --------------------------------------------------------------------------
use App\Models\User;
use App\Models\Company;
use App\Models\UserRole;
use App\Models\MasterValue;
// --------------------------------------------------------------------------
use App\Traits\LogActivityTrait;
// --------------------------------------------------------------------------
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class UserController extends Controller
{
	// ----------------------------------------------------------------------
	use LogActivityTrait;
	// ----------------------------------------------------------------------


	// ----------------------------------------------------------------------
	// Get stored user entries
	// ----------------------------------------------------------------------
	private function get_user_entries( $entry, $company = null ){
		// ------------------------------------------------------------------
		$individual = false; $entry = (object) $entry;
		// ------------------------------------------------------------------
		if( !$company || 'individual' == $company ) $individual = true;
		else $company = Company::find( (int) $company );		
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// Base fields
		// ------------------------------------------------------------------
		$fields = collect([
			'first_name', 'last_name', 'first_name_kana', 'last_name_kana',
			'nickname', 'user_role_id', 'email', 'password'
		]);
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// Company registered users
		// ------------------------------------------------------------------
		if( !$individual && $company ){
			// --------------------------------------------------------------
			// Company relation
			// --------------------------------------------------------------
			$fields->push('company_id');
			// --------------------------------------------------------------

			// --------------------------------------------------------------
			// Real estate company 
			// --------------------------------------------------------------
			if( !empty( $company->kind_real_estate_agent )){
				$fields->push('real_estate_notary_registration');
				if( !empty( $entry->real_estate_notary_registration )){
					$fields = $fields->merge([
						'real_estate_notary_office_id',
						'real_estate_notary_prefecture_id',
						'real_estate_notary_number'
					]);
				}
			}
			// --------------------------------------------------------------

			// --------------------------------------------------------------
			// Except in-house companies
			// --------------------------------------------------------------
			if( !$company->kind_in_house ){
				$fields->push('cooperation_registration');
				if( !empty( $entry->cooperation_registration )){
					$fields = $fields->merge([
						'real_estate_information',
						'real_estate_information_text',
						'registration', 'registration_text',
						'surveying', 'surveying_text',
						'clothes', 'clothes_text',
						'other', 'other_text'
					]);
				}
			}
			// --------------------------------------------------------------
		}
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// Build the dataset
		// ------------------------------------------------------------------
		$dataset = new \stdClass;
		$fields->each( function( $field ) use( $entry, $dataset ){
			if( property_exists( $entry, $field )){
				// ----------------------------------------------------------
				$dataset->{ $field } = $entry->{ $field }; // Assign the property
				// ----------------------------------------------------------
				// Special fields
				// ----------------------------------------------------------
				if( 'password' == $field && !empty( $entry->password )){
					$dataset->password = bcrypt( $entry->password );
				}
				// ----------------------------------------------------------
			}
		});
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		return (array) $dataset;
		// ------------------------------------------------------------------
	}
	// ----------------------------------------------------------------------


	// ----------------------------------------------------------------------
	// Get user updates 
	// ----------------------------------------------------------------------
	private function get_user_updates( $entry, $company = null ){
		// ------------------------------------------------------------------
		$individual = false; $entry = (object) $entry;
		// ------------------------------------------------------------------
		if( !$company || 'individual' == $company ) $individual = true;
		else $company = Company::find( (int) $company );		
		// ------------------------------------------------------------------
		$login = Auth::user();
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// Base fields
		// ------------------------------------------------------------------
		$fields = collect([
			'first_name', 'last_name', 'first_name_kana', 'last_name_kana'
		]);
		// ------------------------------------------------------------------


		// ------------------------------------------------------------------
		// If individual users
		// ------------------------------------------------------------------
		if( $individual ){
			// --------------------------------------------------------------
			$fields = $fields->merge([
				'nickname', 'company_id', 'user_role_id', 'email', 'password'
			]);
			// --------------------------------------------------------------

			// --------------------------------------------------------------
			// Update password
			// --------------------------------------------------------------
			if( !empty( $entry->id ) && $entry->id === $login->id && !empty( $entry->password )){
				$fields->push( 'password' );
			}
			// --------------------------------------------------------------
		}
		// ------------------------------------------------------------------
		
		// ------------------------------------------------------------------
		// Company registered users
		// ------------------------------------------------------------------
		elseif( $company ){
			// --------------------------------------------------------------
			// Inhouse company and accounting advisors
			// --------------------------------------------------------------
			if( !empty( $company->kind_in_house ) || !empty( $company->kind_advisory_accounting )){
				$fields->push('nickname');
				// ----------------------------------------------------------

				// ----------------------------------------------------------
				// If user is an admin
				// ----------------------------------------------------------
				if( 'global_admin' == $login->user_role->name ){
					$fields = $fields->merge([ 'user_role_id', 'email' ]);
				}
				// ----------------------------------------------------------

				// ----------------------------------------------------------
				// If user is updating their own data
				// ----------------------------------------------------------
				if( !empty( $entry->id ) && $entry->id === $login->id && !empty( $entry->password )){
					$fields->push( 'password' );
				}
				// ----------------------------------------------------------
			}
			// --------------------------------------------------------------

			// --------------------------------------------------------------
			// Real estate agents
			// --------------------------------------------------------------
			if( !empty( $company->kind_real_estate_agent )){
				$fields->push('real_estate_notary_registration');
				if( !empty( $entry->real_estate_notary_registration )){
					$fields = $fields->merge([
						'real_estate_notary_office_id',
						'real_estate_notary_prefecture_id',
						'real_estate_notary_number'
					]);
				}
			}
			// --------------------------------------------------------------


			// --------------------------------------------------------------
			// Except in-house companies
			// --------------------------------------------------------------
			if( !$company->kind_in_house ){
				$fields->push('cooperation_registration');
				if( !empty( $entry->cooperation_registration )){
					$fields = $fields->merge([
						'real_estate_information',
						'real_estate_information_text',
						'registration', 'registration_text',
						'surveying', 'surveying_text',
						'clothes', 'clothes_text',
						'other', 'other_text'
					]);
				}
			}
			// --------------------------------------------------------------
		}
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// Build the updates
		// ------------------------------------------------------------------
		$updates = new \stdClass;
		$fields->each( function( $field ) use( $entry, $updates ){
			if( property_exists( $entry, $field )){
				// ----------------------------------------------------------
				$updates->{ $field } = $entry->{ $field }; // Assign the property
				// ----------------------------------------------------------
				// Special fields
				// ----------------------------------------------------------
				if( 'password' == $field && !empty( $entry->password )){
					$updates->password = bcrypt( $entry->password );
				}
				// ----------------------------------------------------------
			}
		});
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		return (array) $updates;
		// ------------------------------------------------------------------
	}
	// ----------------------------------------------------------------------


	// ----------------------------------------------------------------------
	/**
	 * @param $company
	 * @param $param
	 * @return string|void
	 */
	// ----------------------------------------------------------------------
	// Datatable request handler
	// ----------------------------------------------------------------------
	public function show( Request $request ){
		if( !empty( $request->user ) && 'json' == $request->user ){
			// --------------------------------------------------------------
			$company = $request->company;
			// --------------------------------------------------------------

			// --------------------------------------------------------------
			$model = User::with([ 'user_role', 'company' ]);
			if( 'individual' == $company || ! (int) $company ) $model = $model->where( 'company_id', null );
			else $model = $model->where( 'company_id', (int) $company );
			// --------------------------------------------------------------

			// --------------------------------------------------------------
			return DatatablesHelper::json( $model, null, null, null, null );
			// --------------------------------------------------------------
		}
		return abort(404);
	}
	// ----------------------------------------------------------------------

	// ----------------------------------------------------------------------
	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	// ----------------------------------------------------------------------
	public function index( Request $request ){
		// ------------------------------------------------------------------
		if( empty( $request->company )) return abort(404);
		$company = $request->company;
		$user = Auth::user();
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		$data = new \stdClass;
		$data->user = $user;
		$data->individual = false;
		$data->admin = 'global_admin' == $user->user_role->name;
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		if( 'individual' == $company || !(int) $company ){
			$data->individual = true;
			$data->company = (object) array( 'id' => 'individual', 'name' => __( 'users.individual' ));
		} else $data->company = Company::find( (int) $request->company );
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
        // User roles
        // ------------------------------------------------------------------
        $data->roles = UserRole::all();
        // ------------------------------------------------------------------

		// ------------------------------------------------------------------
        // Base properties
        // ------------------------------------------------------------------
        $data->page_title = $data->company->name;
        // ------------------------------------------------------------------
		
		// ------------------------------------------------------------------
		// dd( $data );
		return view( 'backend.user.index', (array) $data );
		// ------------------------------------------------------------------
	}
	// ----------------------------------------------------------------------


	// ----------------------------------------------------------------------
	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	// ----------------------------------------------------------------------
	public function create( Request $request ){
		// ------------------------------------------------------------------
		$individual = false; $user = Auth::user();
		if( empty( $request->company )) return abort(404);
		if( 'global_admin' !== $user->user_role->name ) return abort(404);
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// If it's an individual group
		// ------------------------------------------------------------------
		if( 'individual' == $request->company || !(int) $request->company ){
			$individual = true;
			$company = (object) array( 'id' => 'individual', 'name' => __( 'users.individual' ));
		}
		// ------------------------------------------------------------------
		// Otherwise, find the company
		// ------------------------------------------------------------------
		else {
			$company = Company::with('offices')->find( (int) $request->company );
			if( !$company ) return abort( 404 );
		}
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// Prepare view data
		// ------------------------------------------------------------------
		$data = new \stdClass;
		$data->user = $user;
		$data->company = $company;
		$data->individual = $individual;
		$data->user_roles = UserRole::all();
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// Get real-estate prefectures
		// ------------------------------------------------------------------
		$query = MasterValue::select( 'id', 'key', 'value' )->orderBy( 'sort', 'asc' );
		$data->prefectures = $query->where( 'type', 'realestate_explainer_number_place' )->get();
		// ------------------------------------------------------------------
		
		// ------------------------------------------------------------------
		// Page data
		// ------------------------------------------------------------------
		$data->page_type = 'create';
		$data->page_title = __('label.add') . ' ' . __('label.user') . ' ' . $company->name;
		$data->form_action = route( 'company.user.store', $company->id );
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// User empty model
		// ------------------------------------------------------------------
		$default = (object) array( 'company_id' => $company->id, 'user_role_id' => null );
		// ------------------------------------------------------------------
		if( $individual ) $default->company_id = null; // If creating individual user
		else if( $company->kind_in_house || $company->kind_advisory_accounting ){
			// --------------------------------------------------------------
			$default->user_role_id = 2; // Give user-role a default value if company is in-house or accounting-advisor, 
			// --------------------------------------------------------------
		}
		// ------------------------------------------------------------------
		$data->item = factory( User::class )->state('init')->make( (array) $default );
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// dd( $data );
		return view( 'backend.user.form', (array) $data );
		// ------------------------------------------------------------------
	}
	// ----------------------------------------------------------------------


	// ----------------------------------------------------------------------
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	// ----------------------------------------------------------------------
	public function store( Request $request ){
		try {
			// --------------------------------------------------------------
			$data = (object) $request->all();
			if( !isset( $data->entry )) return response()->json(null);
			// --------------------------------------------------------------
			
			// --------------------------------------------------------------
			// Process the entries and save it to database
			// --------------------------------------------------------------
			$entry = new User;
			$dataset = $this->get_user_entries( $data->entry, $request->company );
			foreach( $dataset as $field => $value ) $entry->{ $field } = $value;
			$entry->save();
			// --------------------------------------------------------------

			// --------------------------------------------------------------
			// Prepare activity log
			// --------------------------------------------------------------
			if( $entry->id ){
				// ----------------------------------------------------------
				$id = $entry->id;
				$name = "{$entry->last_name} {$entry->first_name}";
				$activity = "Created User | {$id} | {$name}";
				$context = 'Individual Group';
				// ----------------------------------------------------------
				if( 'individual' != $request->company && (int) $request->company ){
					$company = Company::find( (int) $request->company );
					if( $company ) $context = "Company: {$company->name}";
				}
				// ----------------------------------------------------------
				$this->saveLog( $activity, $context ); // Save the log
				// ----------------------------------------------------------
			}
			// --------------------------------------------------------------

			// --------------------------------------------------------------
			$request->session()->flash('success', config('const.SUCCESS_CREATE_MESSAGE'));
			return response()->json( $entry );
			// --------------------------------------------------------------
		}
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// Exception
		// ------------------------------------------------------------------
		catch( \Exception $error ){
			return response()->json([
				'status'  => 'error',
				'message' => config('const.FAILED_CREATE_MESSAGE'),
				'error'   => $error->getMessage(),
			], 500 );
		}
		// ------------------------------------------------------------------
	}
	// ----------------------------------------------------------------------
	

	// ----------------------------------------------------------------------
	/**
	 * @param $parent_id
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit( Request $request ){
		// ------------------------------------------------------------------
		$individual = false; $user = Auth::user();
		if( empty( $request->company ) || empty( $request->user )) return abort(404);
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		if( 'global_admin' !== $user->user_role->name ){
			if( (int) $request->user !== $user->id ) return abort(404);
		}
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// If it's an individual group
		// ------------------------------------------------------------------
		if( 'individual' == $request->company || !(int) $request->company ){
			$individual = true;
			$company = (object) array( 'id' => 'individual', 'name' => __( 'users.individual' ));
		}
		// ------------------------------------------------------------------
		// Otherwise, find the company
		// ------------------------------------------------------------------
		else {
			$company = Company::with('offices')->find( (int) $request->company );
			if( !$company ) return abort( 404 );
		}
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// Prepare view data
		// ------------------------------------------------------------------
		$data = new \stdClass;
		$data->user = $user;
		$data->company = $company;
		$data->individual = $individual;
		$data->companies = Company::all();
		$data->user_roles = UserRole::all();
		$data->admin = 'global_admin' == $user->user_role->name;
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// Find the requested user
		// ------------------------------------------------------------------
		$data->item = User::find( (int) $request->user );
		if( !$data->item ) return abort(404);
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// Get real-estate prefectures
		// ------------------------------------------------------------------
		$query = MasterValue::select( 'id', 'key', 'value' )->orderBy( 'sort', 'asc' );
		$data->prefectures = $query->where( 'type', 'realestate_explainer_number_place' )->get();
		// ------------------------------------------------------------------
		
		// ------------------------------------------------------------------
		// Page data
		// ------------------------------------------------------------------
		$data->page_type = 'edit';
		$data->page_title = __('label.edit') . ' ' . __('label.user') . ' ' . $company->name;
		$data->form_action = route( 'company.user.update', [ $company->id, $request->user ]);
		// ------------------------------------------------------------------

		// ------------------------------------------------------------------
		// dd( $data );
		return view( 'backend.user.form', (array) $data );
		// ------------------------------------------------------------------
	}
	// ----------------------------------------------------------------------


	// ----------------------------------------------------------------------
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	// ----------------------------------------------------------------------
	public function update( Request $request ){
		try {
			// --------------------------------------------------------------
			$data = (object) $request->all();
			if( empty( $request->user ) || empty( $data->entry )){
				return response()->json( null );
			}
			// --------------------------------------------------------------

			// --------------------------------------------------------------
			// Get the user
			// --------------------------------------------------------------
			$user = User::find( (int) $request->user );
			if( !$user ) return response()->json( null );
			// --------------------------------------------------------------


			// --------------------------------------------------------------
			// Updatable fields
			// --------------------------------------------------------------
			$updates = $this->get_user_updates( $data->entry, $request->company );
			foreach( $updates as $field => $value ) $user->{ $field } = $value;
			$user->save();
			// --------------------------------------------------------------
			

			// --------------------------------------------------------------
			// Prepare activity log
			// --------------------------------------------------------------
			$id = $user->id;
			$name = "{$user->last_name} {$user->first_name}";
			$activity = "Updated User | {$id} | {$name}";
			$context = 'Individual Group';
			// --------------------------------------------------------------
			if( 'individual' != $request->company && (int) $request->company ){
				$company = Company::find( (int) $request->company );
				if( $company ) $context = "Company: {$company->name}";
			}
			// --------------------------------------------------------------
			$this->saveLog( $activity, $context ); // Save the log
			// --------------------------------------------------------------

			// --------------------------------------------------------------
			return response()->json( $user );
			// --------------------------------------------------------------
		}
		// ------------------------------------------------------------------
		// Exception
		// ------------------------------------------------------------------
		catch( \Exception $error ){
			return response()->json([
				'status'  => 'error',
				'message' => config('const.FAILED_UPDATE_MESSAGE'),
				'error'   => $error->getMessage(),
			], 500 );
		}
		// ------------------------------------------------------------------
	}
	// ----------------------------------------------------------------------


	// ----------------------------------------------------------------------
	/**
	 * @param $parent_id
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	*/
	// ----------------------------------------------------------------------
	public function destroy( Request $request ){
		// ------------------------------------------------------------------
		// Default response
		// ------------------------------------------------------------------
		$user = Auth::user();
		$response = (object) array( 'status' => 'error' );
		// ------------------------------------------------------------------
		
		// ------------------------------------------------------------------
		try {
			// --------------------------------------------------------------
			// User removal run by global admin
			// --------------------------------------------------------------
			if( 'global_admin' == $user->user_role->name && !empty( $request->user )){
				if( (int) $request->user !== $user->id ){
					// ------------------------------------------------------
					// Find the target user
					// ------------------------------------------------------
					$target = User::find( $request->user );
					if( !$target ) return response()->json( $response );
					// ------------------------------------------------------
					
					// ------------------------------------------------------
					// Prepare activity log
					// ------------------------------------------------------
					$id = $target->id;
					$name = "{$target->last_name} {$target->first_name}";
					$activity = "Deleted User | {$id} | {$name}";
					$context = 'Individual Group';
					// ------------------------------------------------------
					if( 'individual' != $request->company && (int) $request->company ){
						$company = Company::find( (int) $request->company );
						if( $company ) $context = "Company: {$company->name}";
					}
					// ------------------------------------------------------
					$target->delete(); // Delete the target user
					$this->saveLog( $activity, $context ); // Save the log
					// ------------------------------------------------------
					
					// ------------------------------------------------------
					$response->status = 'success'; // Update the response
					// ------------------------------------------------------
				}
			}
			// --------------------------------------------------------------

			// --------------------------------------------------------------
			$request->session()->flash('success', config('const.SUCCESS_DELETE_MESSAGE'));
			return response()->json( $response ); // Return response
			// --------------------------------------------------------------
		}
		// ------------------------------------------------------------------
		// Exception
		// ------------------------------------------------------------------
		catch( \Exception $error ){
			$response->message = $error->getMessage();
			return response()->json( $response, 500 );
		}
		// ------------------------------------------------------------------
	}
	// ----------------------------------------------------------------------

	// protected function userOwnerValidator(array $data, $type){
	// 	return Validator::make($data, [
	// 		'display_name' => 'required|string|max:100',
	// 		'email'        => 'required|email|max:255|unique:users,email' . ($type == 'update' ? ',' . $data['id'] : ''),
	// 		'password'     => $type == 'create' ? 'string|min:8|max:255' : 'string|min:8|max:255',
	// 	]);
	// }
	
	// public function editAsUserOwner(){
	// 	$id = Auth::guard('user')->user()->id;
		
	// 	$data['item'] = User::find($id);
		
	// 	$data['page_title'] = __('label.edit') . ' ' . __('label.user');
	// 	$data['form_action'] = route('userowner-update');
	// 	$data['page_type'] = 'edit';
		
	// 	return view('backend.userowner.form', $data);
	// }
	
	// public function updateAsUserOwner(Request $request){
	// 	$id = Auth::guard('user')->user()->id;
	// 	$user_role_id = Auth::guard('user')->user()->user_role_id;
		
	// 	$data = $request->all();
	// 	$currentData = User::find($id);
	// 	$data['password'] = !empty($data['password']) ? $data['password'] : $currentData['password'];
	// 	$data['id'] = $id;                     // Secure id
	// 	$data['user_role_id'] = $user_role_id; // Secure user_role_id
	// 	$this->validator($data, 'update')->validate();
		
	// 	if(Hash::needsRehash($data['password']) && !empty($data['password'])){
	// 		$data['password'] = bcrypt($data['password']);
	// 	}
		
	// 	$currentData->update($data);
		
	// 	return redirect()->back()->with('success', config('const.SUCCESS_UPDATE_MESSAGE'));
	// }

}

