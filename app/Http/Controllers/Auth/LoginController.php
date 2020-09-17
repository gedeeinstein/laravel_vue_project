<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct(){
        $this->middleware('guest')->except('logout');
    }

    protected function loggedOut(Request $request) {
        return redirect('/login');
    }

    /**
     * If user authenticated
     * Redirect user based on users role
     */
    protected function authenticated(Request $request, $user)
    {
        switch ($user->user_role->name){
            case 'no_access':
                Auth::logout();
                return redirect()->route('login')->withErrors(['こちらのアカウントはログイン制限されています。']);
            case 'agent':
                Auth::logout();
                return redirect()->route('login')->withErrors(['こちらのアカウントはログイン制限されています。']);
        }
    }
}