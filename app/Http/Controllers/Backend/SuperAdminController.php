<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\DatatablesHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\LogActivityTrait;
use DataTables;

class SuperAdminController extends Controller
{
    use LogActivityTrait;

    public function __construct(){
    }

    protected function validator( array $data, $type ){
        return Validator::make($data, [
            'display_name'  => 'required|string|max:100',
            'email'         => 'required|email|max:255|unique:admins,email' . ($type == 'update' ? ','.$data['id'] : ''),
            'password'      => $type == 'create' ? 'required|string|min:8|max:255' : 'string|min:8|max:255',
        ]);
    }

    /**
     * @param string parameter - url of custom resources page
     *
     * @return string - Any
     *
     * You may add custom page/api/route, this wrapped middleware as well
     */
    public function show( $param ){
        if( $param == 'json' ){

            $model = admin::where('admin_role_id', 1);
            return DatatablesHelper::json($model,true,false);

        }
        abort(404);
    }

    public function index(){
        $data['page_title'] = __('label.admin');
        return view('backend.superadmin.index', $data);
    }

    public function create(){
        $data['item']       = new Admin();
        $data['page_title'] = __('label.add') . ' ' . __('label.admin');
        $data['form_action']= route('admin.superadmin.store');
        $data['page_type']  = 'create';

        return view('backend.superadmin.form', $data);
    }

    public function store(Request $request){
        $data = $request->all();
        $this->validator($data, 'create')->validate();
        $data['admin_role_id']  = 1;
        $data['password']       = bcrypt($data['password']);
        $new = new Admin();
        $new->fill($data)->save();
        return redirect()->route('admin.superadmin.index')->with('success', config('const.SUCCESS_CREATE_MESSAGE'));
    }

    public function edit($id){
        $data['item']           = Admin::find($id);

        $data['page_title']     = __('label.edit') . ' ' . __('label.admin');
        $data['form_action']    = route('admin.superadmin.update', $id);
        $data['page_type']      = 'edit';

        return view('backend.superadmin.form', $data);
    }

    public function update(Request $request, $id){
        $data               = $request->all();
        $currentAdmin       = Admin::find($id);
        $data['password']   = !empty($data['password']) ? $data['password'] : $currentAdmin['password'];
        $data['id']         = $id;
        $this->validator($data, 'update')->validate();

        if(Hash::needsRehash($data['password'])){
            $data['password'] = bcrypt($data['password']);
        }

        $currentAdmin->update($data);

        return redirect()->route('admin.superadmin.edit', $id)->with('success', config('const.SUCCESS_UPDATE_MESSAGE'));
    }

    public function destroy($id){
        $item = Admin::findOrFail($id);
        $item->delete();

        $admin_email    = $item->email;
        $this->saveLog('Delete SuperAdmin', 'Delete SuperAdmin, Email : ' . $admin_email . '', Auth::user()->id);

        return 1;
    }
}
