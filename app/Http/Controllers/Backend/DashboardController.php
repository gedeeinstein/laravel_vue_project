<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{

    public function __construct(){}

    // ------------------------------------------------------------------
    // Dashboard Index
    // ------------------------------------------------------------------
    public function index(){
        $data['page_title'] = '';
        return view('backend.dashboard.index', $data);
    }

}
