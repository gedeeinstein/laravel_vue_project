<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::check()) {
            switch (Auth::user()->user_role->name){
                case 'no_access':
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['こちらのアカウントはログイン制限されています。']);
                case 'agent':
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['こちらのアカウントはログイン制限されています。']);
                case 'global_admin':
                    return redirect()->intended('dashboard');
                case 'general':
                    return redirect()->intended('dashboard');
                case 'ledger_editor':
                    return redirect()->intended('dashboard');
                case 'registration_manager':
                    return redirect()->intended('dashboard');
                case 'accounting_firm':
                    return redirect()->intended('dashboard');
                case 'accountant':
                    return redirect()->intended('dashboard');
                default:
                    Auth::logout();
                    return redirect('/login');
            }
        }
        return $next($request);
    }
}
