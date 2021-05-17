<?php

namespace App\Http\Middleware;

use App\MyFacades\MySessionGuardFacade;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateOnceWithBasicAuth
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
        //return Auth::onceBasic() ?: $next($request);
        return MySessionGuardFacade::myOnceBasic() ?: $next($request);
    }
}
