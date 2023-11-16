<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $role = strpos($role, '|') ? explode('|', $role) : $role;
     
        // dd(auth()->user()->role);
        // dd(auth()->user()->roles);
        // if(Auth::check() && auth()->user()->hasAnyRole())
        // {
            abort_unless(auth()->user()->hasRole($role), 403, 'Unauthorized.');    
        // }
        return $next($request);
    }
}
