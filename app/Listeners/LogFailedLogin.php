<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\LogActivityTrait;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\URL;
class LogFailedLogin
{
    use LogActivityTrait;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        //
        $this->request  = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Failed  $event
     * @return void
     */
    public function handle( Failed $event ){
        // ------------------------------------------------------------------
        $url = URL::current();
        $email = $this->request->email;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $found = false;
        if( \strpos( $url, 'admin' )){
            $found = Admin::where( 'email', $email )->first();
        } else {
            $found = User::where( 'email', $email )->first();
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $detail = "Email : {$email}";
        if( $found ){
            // --------------------------------------------------------------
            $id = $found->id;
            $activity = 'Login Failed (Invalid password)';
            // --------------------------------------------------------------
        } else {
            // --------------------------------------------------------------
            $id = null;
            $activity = 'Login Failed (User not found)';
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $this->saveLog( $activity, $detail, $email, $id );
        // ------------------------------------------------------------------
    }
}
