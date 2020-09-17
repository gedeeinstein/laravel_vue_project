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

class QAManagerCategoryController extends Controller {

    use LogActivityTrait;

    // ----------------------------------------------------------------------
    // Validation Rule
    // ----------------------------------------------------------------------
    protected function validator( array $data ){
        return Validator::make( $data, [
            'name'     => 'required|string|max:256',
        ]);
    }

    // ----------------------------------------------------------------------
    // Json Datatable
    // ----------------------------------------------------------------------
    public function show( $param ){
        if( $param == 'json' ){
            $model = OtherAdditionalQaCategory::select('*');
            return DatatablesHelper::json( $model, false, false, null );
        }
        abort(404);
    }

    // ----------------------------------------------------------------------
    // Index QA Manager Category Page
    // ----------------------------------------------------------------------
    public function index(){
        $data = new \stdClass;
        $data->page_title = __('label.qamanager.category_index');
        $data->user_role = auth()->user()->user_role->name;
        return view( 'backend.qamanager.category.index', (array) $data );
    }


    // ----------------------------------------------------------------------
    // API Store QA Manager Category
    // ----------------------------------------------------------------------
    public function store( Request $request ){
        try {
            $data = $request->all();
            $this->validator( $data )->validate();
            $qa_category = new OtherAdditionalQaCategory();
            $qa_category->fill( $data );
            $qa_category->order = OtherAdditionalQaCategory::max('order')+1;
            $qa_category->created_at = Carbon::now();
            $qa_category->updated_at = Carbon::now();
            $qa_category->save();

            // return succes message
            return response()->json([
                'status'  => 'success',
                'message' => __('label.success_create_message'),
            ]);

        } catch (\Exception $error) {
            // return error message
            Log::error([
                'message'   => $error->getMessage(),
                'file'      => $error->getFile().':'.$error->getLine(),
                'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => __('label.failed_create_message'),
                'error'   => $error->getMessage()
            ], 500);
        }
    }


    // ----------------------------------------------------------------------
    // API Database Update QA Manager Category
    // ----------------------------------------------------------------------
    public function update( Request $request, $id ){
        try {
            $item = OtherAdditionalQaCategory::find( $id );
            $data = $request->all();
            $this->validator( $data )->validate();

            $data['updated_at'] = Carbon::now();
            $item->update( $data );

            // return succes message
            return response()->json([
                'status'  => 'success',
                'message' => __('label.success_update_message'),
            ]);

        } catch (\Exception $error) {
            // return error message
            Log::error([
                'message'   => $error->getMessage(),
                'file'      => $error->getFile().':'.$error->getLine(),
                'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => __('label.failed_update_message'),
                'error'   => $error->getMessage()
            ], 500);
        }

    }

    // ----------------------------------------------------------------------
    // API Database Delete QA Manager Category
    // ----------------------------------------------------------------------
    public function destroy( $id ){
        try {
            $delete = OtherAdditionalQaCategory::findOrFail($id);
            $delete->delete();

            // reorder category after delete to prevent ordering issues
            $categories = OtherAdditionalQaCategory::orderBy('order', 'asc')->get();
            foreach ($categories as $order => $category) {
                $order+= 1;
                OtherAdditionalQaCategory::where('id', $category->id)->update(['order' => $order]);
            }

            // delete related checklist on category is deleted
            $checklists = OtherAdditionalQaCheck::whereNull('category_id')->get();
            foreach ($checklists as $checklist) {
                $checklist->delete();
            }

            // Save to log
            $admin = Auth::user();
            $detail = "Delete Q&A Category {$delete->name}";
            $this->saveLog( 'Delete Q&A Category', $detail, $admin->email, $admin->id );

            // return succes message
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
            return response()->json([
                'status'  => 'error',
                'message' => __('label.failed_delete_message'),
                'error'   => $error->getMessage()
            ], 500);
        }

    }

    // ----------------------------------------------------------------------
    // API Ordering Handler
    // ----------------------------------------------------------------------
    public function order( Request $request ){
        $data = $request;
        $category_order =  new OtherAdditionalQaCategory();
        try {
            switch ($data->position) {
                case 'top':
                    if ($data->order == 1) break;
                    $order = 1;
                    $category_order->where('id', $data->id)->update(['order' => $order]);
                    $categories = OtherAdditionalQaCategory::where('id', '<>', $data->id)->orderBy('order', 'asc')->get();
                    foreach ($categories as $category) {
                        $order++;
                        $category_order->where('id', $category->id)->update(['order' => $order]);
                    }
                    return response()->json([
                        'status'  => 'success',
                        'message' => '最上段へ移動完成',
                    ]);
                case 'up':
                    if ($data->order == 1) break;
                    $order = $data->order-1;
                    $next = $order+1;
                    $category_order->where('order', $order)->update(['order' => $next]);
                    $category_order->where('id', $data->id)->update(['order' => $order]);
                    return response()->json([
                        'status'  => 'success',
                        'message' => '上へ移動完成',
                    ]);
                case 'down':
                    $last = $category_order->count();
                    if ($data->order == $last) break;
                    $order = $data->order+1;
                    $prev = $order-1;
                    $category_order->where('order', $order)->update(['order' => $prev]);
                    $category_order->where('id', $data->id)->update(['order' => $order]);
                    return response()->json([
                        'status'  => 'success',
                        'message' => '下へ移動完成',
                    ]);
                case 'bottom':
                    $last = $category_order->count();
                    if ($data->order == $last) break;
                    $category_order->where('id', $data->id)->update(['order' => $last]);
                    $categories = OtherAdditionalQaCategory::where('id', '<>', $data->id)->orderBy('order', 'desc')->get();
                    foreach ($categories as $category) {
                        $last--;
                        $category_order->where('id', $category->id)->update(['order' => $last]);
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
            Log::error([
                'message'   => $error->getMessage(),
                'file'      => $error->getFile().':'.$error->getLine(),
                'route'     => $_SERVER['REQUEST_METHOD'].':'.$_SERVER['REQUEST_URI'],
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => __('label.common_error_message'),
                'error'   => $error->getMessage()
            ], 500);
        }
    }

}
