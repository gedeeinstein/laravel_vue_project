<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Project;
// --------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class ProjectTemplateController extends Controller {
    // ----------------------------------------------------------------------
    public function index(){
        $data = new \stdClass;
        $data->page_title = 'Project Template';
        return view( 'backend.project.template.form', (array) $data );
    }
    // ----------------------------------------------------------------------
    public function show(){

    }
}
// --------------------------------------------------------------------------
