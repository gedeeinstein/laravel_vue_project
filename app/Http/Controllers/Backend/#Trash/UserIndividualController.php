<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\DatatablesHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogActivityTrait;
use DataTables;

class UserIndividualController extends Controller
{
	use LogActivityTrait;
	
	private $users;
	
	/**
	 * UserIndividualController constructor.
	 */
	public function __construct(){
		$this->users = new User();
	}
	
	/**
	 * @return string|void
	 */
	public function show(){
		if('json'){
			$model = $this->users::where('company_id', null);
			return DatatablesHelper::json($model, true, true, null, null, null);
		}
		return abort(404);
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index(){
		$data['parent_id'] = 0;
		$data['company_name'] = __('users.individual');
		$data['page_title'] = __('users.individual');
		return view('backend.userindividual.index', $data);
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create(){
		$data['company_name'] = __('users.individual');
		$data['item'] = factory( User::class )->state('init')->make();
		$data['page_title'] = __('label.add') . ' ' . __('label.user');
		$data['form_action'] = route('admin.user.individual.store');
		$data['page_type'] = 'create';
		return view('backend.userindividual.form', $data);
	}
	
	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function store(Request $request){
		try{
			$data = (object)$request->all();
			if(!isset($data->entry)){
				return response()->json(null);
			}
			
			// $data['password'] = !empty($data['password']) ? bcrypt($data['password']) : '';
			$createNewUserIndividual = $this->users::updateOrCreate($data->entry);
			
			$this->saveLog('Create new User ', 'in Individual company', Auth::user()->email, Auth::user()->id);// log admin activity
			$request->session()->flash('success', config('const.SUCCESS_CREATE_MESSAGE'));
			return response()->json($createNewUserIndividual);
		}
		catch(\Exception $error){
			// User not found
			return response()->json([
				'status'  => 'error',
				'message' => config('const.FAILED_CREATE_MESSAGE'),
				'error'   => $error->getMessage(),
			], 422);
		}
	}
	
	/**
	 * @param $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit($id){
		
		$data['role'] = Auth::user()['adminRole'];
		$data['item'] = $this->users::find($id);
		$data['page_title'] = __('users.individual') . ' ' . __('label.user');
		$data['form_action'] = route('admin.user.individual.update', $id);
		$data['page_type'] = 'edit';
		return view('backend.userindividual.form', $data);
	}
	
	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function update(Request $request){
		try{
			$data = (object)$request->all();
			$updateUser = $this->users::find($data->entry['id']);;
			
			if(!isset($data)){
				return response()->json(null);
			}
			
			// $data['password'] = !empty($data['password']) ? $data['password'] : $currentData['password'];
			// if(Hash::needsRehash($data['password']) && !empty($data['password'])){
			//     $data['password'] = bcrypt($data['password']);
			// }
			
			$data->entry['updated_at'] = Carbon::now();
			$updateUser->update($data->entry);
			$this->saveLog('Update User: ' . $data->entry['first_name'].$data->entry['last_name'], 'for Individual company', Auth::user()->email, Auth::user()->id);// log admin activity
			return response()->json($data->entry);
		}
		catch(\Exception $error){
			return response()->json([
				'status'  => 'error',
				'message' => config('const.FAILED_UPDATE_MESSAGE'),
				'error'   => $error->getMessage(),
			], 422);
		}
		
	}
	
	/**
	 * @param $id
	 * @return JsonResponse
	 */
	public function destroy($id){
		try{
			$user = $this->users::findOrFail($id);
			$this->saveLog('Delete User Individual', $user->first_name.$user->last_name, Auth::user()->email, Auth::user()->id);// log admin activity
			$user->delete();
			return response()->json([
				'status'  => 'success',
				'message' => __('label.jsInfoDeletedData'),
			]);
		}
		catch(\Exception $error){
			return response()->json([
				'status'  => 'error',
				'message' => config('const.FAILED_DELETE_MESSAGE'),
				'error'   => $error->getMessage(),
			], 500);
		}
	}
	
}