<?php

namespace App\Http\Middleware;


use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        Log::debug('request----------:' . $request->fullUrl() . '---:', $request->input());
        return $next($request);
    }

}
