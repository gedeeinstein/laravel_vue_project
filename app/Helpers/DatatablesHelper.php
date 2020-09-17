<?php

namespace App\Helpers;

use DataTables;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class DatatablesHelper
{
    /**
     * @param object $eloquent
     * @param bool   $btn_edit
     * @param bool   $btn_delete
     * @param null   $btn_custom
     * @param null   $btn_nested
     * @param null   $nested_parent_id
     * @return string json - pagination, search etc. processed by Yajra Datatable package
     */
    public static function json($eloquent, $btn_edit = true, $btn_delete = true, $btn_custom = null, $btn_nested = null, $nested_parent_id = null){

        return Datatables::of($eloquent)->addColumn('action', function($query) use ($btn_edit, $btn_delete, $btn_custom, $btn_nested, $nested_parent_id){
            // ------------------------------------------
            $routeCurrent = Route::currentRouteName();
            $routeShow = str_replace('show', 'show', $routeCurrent);
            $routeEdit = str_replace('show', 'edit', $routeCurrent);
            $routeDestroy = str_replace('show', 'destroy', $routeCurrent);

            // ------------------------------------------

            // ------------------------------------------
            if(!empty($nested_parent_id)){
                $url_edit = URL::route($routeEdit, [$nested_parent_id, $query->id]);
                $url_delete = URL::route($routeDestroy, [$nested_parent_id, $query->id]);
            }
            else{
                // --------------------------------------
                // If edit button is a function, call it to get the returned value
                // --------------------------------------
                if(is_callable($btn_edit))
                    $url_edit = $btn_edit($query);
                else $url_edit = !empty($btn_edit) ? URL::route($routeEdit, $query->id) : '';
                // --------------------------------------

                // --------------------------------------
                // If delete button is a function, call it to get the returned value
                // --------------------------------------
                if(is_callable($btn_delete))
                    $url_delete = $btn_delete($query);
                else $url_delete = !empty($btn_delete) ? URL::route($routeDestroy, $query->id) : '';
                // --------------------------------------
            }
            // ------------------------------------------


            // ------------------------------------------
            $buttons = [];
            if(!empty($btn_edit)){
                $buttons[] = '<a title="Edit Data" href="' . $url_edit . '" class="btn btn-info"><i class="fas fa-edit"></i></a>';
            }
            if(!empty($btn_custom)){
                $buttons[] = $btn_custom;
            }
            if(!empty($btn_nested)){
                $buttons[] = '<a href="' . URL::route($routeShow, $query->id) . '/' . $btn_nested['current_nest'] . '" class="btn btn-' . $btn_nested['style'] . '"><i class="fas fa-' . $btn_nested['icon'] . '"></i></a>';
            }
            if(!empty($btn_delete)){
                $buttons[] = '<a title="Delete Data" href="" data-remote="' . $url_delete . '" class="btn btn-danger deleteData"><i class="fas fa-trash"></i></a>';
            }
            // ------------------------------------------

            $render = "";
            if(count($buttons) > 0){
                $render .= '<div class="control-buttons btn-group-normal d-flex justify-content-center">';
                foreach($buttons as $button){
                    $render .= $button;
                }
                $render .= '</div>';
            }
            return $render;
        })->editColumn('id', '{{$id}}')->toJson( JSON_NUMERIC_CHECK );
    }
}
