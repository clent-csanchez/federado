<?php

namespace Clent\Federado\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Hash;

class ValidateIncomingSessionToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $secret = str_replace('-',' ',config('federado.secret'));

        // abort_if( !(Hash::check($secret,$request->get('token')) ),404,'invaliad token');

        return $next($request);
    }
}