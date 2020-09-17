<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogActivityTrait;
use Illuminate\Support\Facades\URL;
class LogSuccessfulLogout
{
    use LogActivityTrait;
    /**
    * Create the event listener.
    *
    * @return void
    */
    public function __construct()
    {
        //
    }

    /**
    * Handle the event.
    *
    * @param  Logout  $event
    * @return void
    */
    // ----------------------------------------------------------------------
    public function handle( Logout $event ){
        // ------------------------------------------------------------------
        $id = Auth::id();
        $activity = 'Logout Success';
        $email = Auth::user()->email;
        $detail = "Email : {$email}";
        // ------------------------------------------------------------------
        $this->saveLog( $activity, $detail, $email, $id );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
