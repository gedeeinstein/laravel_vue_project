<?php

namespace App\Listeners;

use App\Traits\LogActivityTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Login;
class LogSuccessfulLogin
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
    * @param  Login  $event
    * @return void
    */
    // ----------------------------------------------------------------------
    public function handle( Login $event ){
        // ------------------------------------------------------------------
        $id = Auth::id();
        $activity = 'Login Success';
        $email = Auth::user()->email;
        $detail = "Email : {$email}";
        // ------------------------------------------------------------------
        $this->saveLog( $activity, $detail, $email, $id );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
