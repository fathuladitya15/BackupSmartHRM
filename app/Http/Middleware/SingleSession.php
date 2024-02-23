<?php

namespace App\Http\Middleware;

use Auth;
use Session;
use Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SingleSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check())
        {
            // If current session id is not same with last_session column
            if(Auth::user()->last_session != Session::getId())
            {
               // do logout
               Auth::logout();

               // Redirecto login page
              return Redirect::to('login');
            }
         }
        return $next($request);
    }
}
