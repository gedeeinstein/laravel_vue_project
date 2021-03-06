<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Api;
// --------------------------------------------------------------------------
use Carbon\Carbon;
// --------------------------------------------------------------------------
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class ServerApiController extends Controller {
	// ----------------------------------------------------------------------

	// ----------------------------------------------------------------------
	// Handle server time request
	// ----------------------------------------------------------------------
	public function time(){
		$time = Carbon::now();
		return response()->json( $time->toDateTimeString() );
	}
	// ----------------------------------------------------------------------
}

