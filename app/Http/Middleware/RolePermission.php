<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class RolePermission
{
    /**
     * Handle an incoming request.
     * db admin_roles->name == name
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param array                    $role
     * @return mixed
     */
    public function handle($request, Closure $next, ...$role){
        if(in_array(Auth::user()->user_role->name, $role))
        {
            // handle sessions language for default localization
            if(!$request->session()->has('language')){
                $request->session()->put('language', App::getLocale());
            }
            App::setLocale( $request->session()->get('language') );

            return $next($request);
        }
        else{
            return redirect('/login');
        }
    }
}
