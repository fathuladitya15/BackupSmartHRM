<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{

    public function handle(Request $request, Closure $next,$role)
    {
        $roles = explode(':', $role);
        foreach($roles as $role) {
            if($request->user()->roles == $role) {
                return $next($request);
            }
        }

        $notify[] = ['error', 'Error Code : 404 Not Found'];
        return redirect('home')->withNotify($notify);
    }
}
