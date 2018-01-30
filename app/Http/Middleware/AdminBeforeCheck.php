<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class AdminBeforeCheck
{

    protected $noLoginRoutes = ['login'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {

        $isLogin = session('is_login', 0);
        $routeName = $request->route()->getName();
        if(!in_array($routeName, $this->noLoginRoutes)){
            //需要效验登录
            if(!$isLogin){
                return Redirect::action('Admin\AdminController@login');
            }
        }

        // 如果已经登录过了，直接调新页面
        if($isLogin && $routeName == 'login'){
            return Redirect::action('Admin\DeviceController@list');
        }

        Log::debug('admin route : ' . $request->route()->getActionName());

//        $a = session()->all();
//        Log::debug('session  : ' . json_encode($a));

        return $next($request);
    }

}
