<?php

namespace App\Http\Middleware;


use App\Services\UserService;
use Illuminate\Http\Request;

class TokenLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if($token = $request->input('token')){
            UserService::getUserInfoByToken($token);
        }
        return $next($request);
    }

}
