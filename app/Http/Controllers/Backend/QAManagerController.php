<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Helpers\DatatablesHelper;
use App\Http\Controllers\Controller;
use App\Models\OtherAdditionalQaCheck;
use App\Models\OtherAdditionalQaCategory;
use App\Traits\LogActivityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class QAManagerController extends Controller {

    use LogActivityTrait;

    private $input_types = [];
    private $input_status = [1 => '有効', 0 => '無効'];

    public function __construct(){
        $this->input_types = config('const.QA_INPUT_TYPES');
    }

    // ----------------------------------------------------------------------
    // Validation Rule
    // ----------------------------------------------------------------------
    protected function validator( array $data ){
        return Validator::make( $data, [
            'category_id'  => 'required|integer',
            'question'     => 'required|string|max:256',
            'input_type'   => 'required|integer',
            'status'       => 'required|integer'
        ]);
    }

    // ----------------------------------------------------------------------
    // Json Datatable
    // ----------------------------------------------------------------------
    public function show( $param ){
        if( $param == 'json' ){
            $model = OtherAdditionalQaCheck::with('category');
            return DatatablesHelper::json( $model, true, false, null );
        }
        abort(404);
    }

    // ----------------------------------------------------------------------
    // Index QA Manager page
    // ----------------------------------------------------------------------
    public function index(){
        $data = new \stdClass;
        $data->page_title = __('label.qamanager.list');
        $data->user_role = auth()->user()->user_role->name;
        return view( 'backend.qamanager.index', (array) $data );
    }

    // ----------------------------------------------------------------------
    // Create QA Manager page
    // ----------------------------------------------------------------------
    public function create(){
        if(!auth()->user()->isGlobalAdmin()){
            abort(404);
        }
        $data = new \stdClass;
        $data->item = new OtherAdditionalQaCheck();
        $data->categories = OtherAdditionalQaCategory::pluck('name', 'id');
        $data->input_types = $this->input_types;
        $data->input_status = $this->input_status;
        $data->page_type   = 'create';
        $data->page_title  = __('label.add') . ' ' .  __('label.qamanager.index');
        $data->form_action = route('qamanager.store');
        return view( 'backend.qamanager.form', (array) $data );
    }

    // ----------------------------------------------------------------------
    // Store QA Manager
    // ----------------------------------------------------------------------
    public function store( Request $request ){
        try {
            $data = $request->all();
            $this->validator( $data )->validate();
            $qa_manager = new OtherAdditionalQaCheck();
            $qa_manager->fill( $data );
            $choices = json_decode($request->choices);
            if (isset($choices)) {
                $choice_data = [];
                foreach ($choices as $key => $choice) {
                    $choice_data[] = $choice->value;
                }
                $qa_manager->choices = json_encode($choice_data);
            }
            $qa_manager->order = OtherAdditionalQaCheck::where('category_id', $data['category_id'])->max('order')+1;
            $qa_manager->created_at = Carbon::now();
            $qa_manager->updated_at = Carbon::now();
            $qa_manager->save();
            return redirect()
                ->route('qamanager.index' )
                ->with( 'success', __('label.success_create_message'));
        }
        catch (\Exception $error) {
            Log::error([
                'message'   => $error->getMessage(),
                'file'      => $error->getFile().':'.$error->getLine(),
                'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
            ]);
            return redirect()->route('qamanager.index' )
                             ->withErrors(__('label.failed_create_message'));
        }
    }

    // ----------------------------------------------------------------------
    // Edit QA Manager page
    // ----------------------------------------------------------------------
    public function edit( $id ){
        if(!auth()->user()->isGlobalAdmin()){
            abort(404);
        }
        $data = new \stdClass;
        $data->item = OtherAdditionalQaCheck::find($id);
        $data->categories = OtherAdditionalQaCategory::pluck('name', 'id');
        $data->input_types = $this->input_types;
        $data->input_status = $this->input_status;
        $data->page_type   = 'edit';
        $data->page_title  = __('label.edit') . ' ' .  __('label.qamanager.index');
        $data->form_action = route('qamanager.update', $id );
        return view( 'backend.qamanager.form', (array) $data );
    }

    // ----------------------------------------------------------------------
    // Database update
    // ----------------------------------------------------------------------
    public function update( Request $request, $id ){
        try {
            $item = OtherAdditionalQaCheck::find( $id );
            $data = $request->all();
            $this->validator( $data )->validate();

            $data['updated_at'] = Carbon::now();
            $choices = json_decode($request->choices);
            if (isset($choices)) {
                $choice_data = [];
                foreach ($choices as $key => $choice) {
                    $choice_data[] = $choice->value;
                }
                $data['choices'] = json_encode($choice_data);
            }
            $item->update( $data );
            return redirect()->route('qamanager.index' )
                             ->with( 'success', __('label.success_update_message'));
        }
        catch (\Exception $error) {
            Log::error([
                'message'   => $error->getMessage(),
                'file'      => $error->getFile().':'.$error->getLine(),
                'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
            ]);
            return redirect()->route('qamanager.index' )
                             ->withErrors(__('label.failed_update_message'));
        }
    }

    // ----------------------------------------------------------------------
    // Delete QA manager item
    // ----------------------------------------------------------------------
    public function destroy( $id ){
        try {
            $delete = OtherAdditionalQaCheck::findOrFail($id);
            $delete->delete();

            // reorder category after delete to prevent ordering issues
            $lists = OtherAdditionalQaCheck::orderBy('order', 'asc')->get();
            foreach ($lists as $order => $list) {
                $order+= 1;
                OtherAdditionalQaCheck::where('id', $list->id)->update(['order' => $order]);
            }

            // Save to log
            $user = Auth::user();
            $detail = "Delete Q&A {$delete->question}";
            $this->saveLog( 'Delete Q&A', $detail, $user->email, $user->id );

            // return succes message
            request()->session()->flash('success', __('label.success_delete_message'));
            return response()->json([
                'status'  => 'success',
                'message' => __('label.success_delete_message'),
            ]);

        } catch (\Exception $error) {
            // return error message
            Log::error([
                'message'   => $error->getMessage(),
                'file'      => $error->getFile().':'.$error->getLine(),
                'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
            ]);
            request()->session()->flash('error', __('label.failed_delete_message'));
            return response()->json([
                'status'  => 'error',
                'message' => __('label.failed_delete_message'),
                'error'   => $error->getMessage()
            ], 500);
        }

    }

    // ----------------------------------------------------------------------
    // API ordering handler
    // ----------------------------------------------------------------------
    public function order( Request $request ){
        $data = $request;
        $questions =  new OtherAdditionalQaCheck();
        $base_index = $data->order;

        try {
            switch ($data->position) {
                case 'top':
                    if ($data->order == 1) break;
                    $order = 1;
                    $questions->where('id', $data->id)->update(['order' => $order]);
                    $categories = OtherAdditionalQaCheck::where('category_id', $data->category_id)->where('id', '<>', $data->id)->orderBy('order', 'asc')->get();
                    foreach ($categories as $category) {
                        $order++;
                        $questions->where('id', $category->id)->update(['order' => $order]);
                    }
                    return response()->json([
                        'status'  => 'success',
                        'message' => '最上段へ移動完成',
                    ]);
                case 'up':
                    if ($data->order == 1) break;
                    $order = $data->order-1;
                    $next = $order+1;
                    $questions->where('category_id', $data->category_id)->where('order', $order)->update(['order' => $next]);
                    $questions->where('id', $data->id)->update(['order' => $order]);
                    return response()->json([
                        'status'  => 'success',
                        'message' => '上へ移動完成',
                    ]);
                case 'down':
                    $last = $questions->where('category_id', $data->category_id)->count();
                    if ($data->order == $last) break;
                    $order = $data->order+1;
                    $prev = $order-1;
                    $questions->where('category_id', $data->category_id)->where('order', $order)->update(['order' => $prev]);
                    $questions->where('id', $data->id)->update(['order' => $order]);
                    return response()->json([
                        'status'  => 'success',
                        'message' => '下へ移動完成',
                    ]);
                case 'bottom':
                    $last = $questions->where('category_id', $data->category_id)->count();
                    if ($data->order == $last) break;
                    $questions->where('id', $data->id)->update(['order' => $last]);
                    $categories = OtherAdditionalQaCheck::where('category_id', $data->category_id)->where('id', '<>', $data->id)->orderBy('order', 'desc')->get();
                    foreach ($categories as $category) {
                        $last--;
                        $questions->where('id', $category->id)->update(['order' => $last]);
                    }
                    return response()->json([
                        'status'  => 'success',
                        'message' => '最下段へ移動完成',
                    ]);
                default:
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'no ordering'
                    ], 500);
            }

        } catch (\Exception $error) {
            return response()->json([
                'status'  => 'error',
                'message' => __('label.common_error_message'),
                'error'   => $error->getMessage()
            ], 500);
        }
    }

}
