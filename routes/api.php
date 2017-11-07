<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::any('/user/loginByCode', 'Api\UserController@loginByCode');

Route::any('/user/checkLogin','Api\UserController@checkLogin');

Route::any('/user/bindPhoneForWx', 'Api\UserController@bindPhoneForWx');

Route::any('/user/login', 'Api\UserController@login');

Route::any('/user/checkToken', 'Api\UserController@checkToken');

Route::any('/test', function(){

});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
