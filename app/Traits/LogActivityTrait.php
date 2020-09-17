<?php

namespace App\Traits;

use App\Models\LogActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

trait LogActivityTrait
{
    /**
     * saving user logging to database.
     * @param $activity
     * @param $detail
     * @param $user_id
     */
    private function saveLog( $activity, $detail, $email = null, $id = null ){
        // ------------------------------------------------------------------
        $auth_id = auth()->user()->id ?? null;
        $log = new LogActivity();
        $log->user_id = $id ?? $auth_id ;
        $log->activity = $activity;
        $log->detail = $detail;
        $log->email = $email ?? auth()->user()->email;
        $log->ip = request()->ip();
        $log->access_time = Carbon::now();
        // ------------------------------------------------------------------
        $log->save();
        // ------------------------------------------------------------------
    }
}
