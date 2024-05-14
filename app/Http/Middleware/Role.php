<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Debugbar;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // dd($roles);

        if (!Auth::user())
            return redirect()->route('user.login');
        // if (Auth::user()->role === $role) {

        //     return $next($request);
        // }
        foreach($roles as $role) {
            if(Auth::user()->role === $role){
               return $next($request);
             }
        }
        return redirect()->back()->with('error', 'unauthorized');
    }
}
