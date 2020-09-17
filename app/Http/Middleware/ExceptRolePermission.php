<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ExceptRolePermission
{
    /**
     * Handle restricted access by user role
     * db user_role->name == name
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param array                    $role
     * @return mixed
     */
    public function handle($request, Closure $next, ...$except){
        if(in_array(Auth::user()->user_role->name, $except))
        {
            return redirect()->route('login')->withErrors(['こちらのページのアクセス制限されています。']);
        }
        return $next($request);
    }
}
