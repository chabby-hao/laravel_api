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
Route::middlewareGroup('admin', [\App\Http\Middleware\AdminBeforeCheck::class,]);

Route::any('/device/list', 'Admin\DeviceController@list');
Route::any('/device/add','Admin\DeviceController@add');
Route::any('/device/remoteUpgrade','Admin\DeviceController@remoteUpgrade');
Route::any('/device/slaveBinManage','Admin\DeviceController@slaveBinManage');
Route::any('/device/remoteTunnel','Admin\DeviceController@remoteTunnel');

Route::any('/test', function () {
    $d = Route::currentRouteAction();
    var_dump($d);
    exit;
    $a = view('admin/test');
    var_dump($a->render());
    //echo $a;
});