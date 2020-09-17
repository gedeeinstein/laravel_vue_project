<?php

namespace App\Http\Controllers\Backend\Master;

use App\Models\SaleMemo;
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// --------------------------------------------------------------------------

class MasterSaleListMemoController extends Controller
{
        // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Handle create request
    // ----------------------------------------------------------------------
    public function create( Request $request ){
        $user = Auth::user();
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'success';
        $response->heading = __('label.success');
        $response->text = __('label.success_create_message');
        // ------------------------------------------------------------------

        

        // ------------------------------------------------------------------
        // Create the new memo
        // ------------------------------------------------------------------
        if( !empty( $request->entry )){
            $entry = (object) $request->entry;
            if( !empty( $entry->content ) && !empty( $entry->mas_section_id )){
                // ----------------------------------------------------------
                $model = new SaleMemo();
                $model->mas_section_id = $entry->project_id;
                $model->content = $entry->content;
                $model->author = $entry->user_id;
                $model->is_deleted = false;
                // ----------------------------------------------------------
                $model->save(); // Save as new entry
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                if( $model->id ){
                    $response->entry = SaleMemo::with('user')->find( $model->id );
                }
                // ----------------------------------------------------------
                else {
                    $response->status = 'error';
                    $response->heading = __('label.error');
                    $response->text = __('label.failed_create_message');
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
    // Handle update request
    // ----------------------------------------------------------------------
    public function update( Request $request ){
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'success';
        $response->heading = __('label.success');
        $response->text = __('label.success_update_message');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Process the updates
        // ------------------------------------------------------------------
        if( !empty( $request->entry )){
            $entry = (object) $request->entry;
            if( !empty( $entry->id )){
                // ----------------------------------------------------------
                $model = SaleMemo::find( $entry->id );
                if( !$model ){
                    $response->status = 'error';
                    $response->heading = __('label.error');
                    $response->text = __('label.failed_update_message');
                }
                // ----------------------------------------------------------
                else {
                    // ------------------------------------------------------
                    if( !empty( $entry->content )){
                        $model->content = $entry->content;
                    }
                    // ------------------------------------------------------
                    $model->save(); // Save as new entry
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
    // Handle delete request
    // A deleted memo will be set as disabled instead of actually deleting it
    // ----------------------------------------------------------------------
    public function delete( $id ){
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'success';
        $response->heading = __('label.success');
        $response->text = __('label.success_delete_message');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Process the updates
        // ------------------------------------------------------------------
        if( !empty( $id )){
            $model = SaleMemo::find( $id );
            // --------------------------------------------------------------
            if( !$model ){
                $response->status = 'error';
                $response->heading = __('label.error');
                $response->text = __('label.failed_delete_message');
            }
            // --------------------------------------------------------------
            else {
                $model->is_deleted = true;
                $model->save();
            };
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json( $response );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
