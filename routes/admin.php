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
Route::middlewareGroup('admin', [
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \App\Http\Middleware\AdminBeforeCheck::class,
]);

Route::any('/home/index', 'Admin\HomeController@index');
Route::any('/home/show', 'Admin\HomeController@show');
Route::any('/home/detailData', 'Admin\HomeController@detailData');
Route::any('/home/deviceNoList', 'Admin\HomeController@deviceNoList');
Route::any('/home/detailDataByMonth', 'Admin\HomeController@detailDataByMonth');
Route::any('/home/dailyDetail', 'Admin\HomeController@dailyDetail');
Route::any('/home/monthDetail', 'Admin\HomeController@monthDetail');

Route::any('/device/deviceList', 'Admin\DeviceController@deviceList');
Route::any('/device/list', 'Admin\DeviceController@list');
Route::any('/device/add', 'Admin\DeviceController@add');
Route::any('/device/edit', 'Admin\DeviceController@edit');
Route::any('/device/remoteUpgrade', 'Admin\DeviceController@remoteUpgrade');
Route::any('/device/slaveBinManage', 'Admin\DeviceController@slaveBinManage');
Route::any('/device/remoteTunnel', 'Admin\DeviceController@remoteTunnel');
Route::any('/device/rebootSlave', 'Admin\DeviceController@rebootSlave');
Route::any('/device/statCostDetail', 'Admin\DeviceController@statCostDetail');
Route::any('/device/deviceConfig', 'Admin\DeviceController@deviceConfig');

Route::any('/user/list', 'Admin\UserController@list');
Route::any('/user/feedback', 'Admin\UserController@feedback');
Route::any('/user/presentSet', 'Admin\UserController@presentSet');

Route::any('/orders/list', 'Admin\OrdersController@list');

Route::any('/charge/list', 'Admin\ChargeController@list');

Route::any('/refunds/list', 'Admin\RefundsController@list');

Route::any('/admins/list', 'Admin\AdminController@list');
Route::any('/admins/add', 'Admin\AdminController@add');
Route::any('/admins/edit', 'Admin\AdminController@edit');
Route::any('/admins/login', 'Admin\AdminController@login')->name('login');
Route::any('/admins/logout', 'Admin\AdminController@logout');

Route::any('/activity/cardsList','Admin\ActivityController@cardsList');
Route::any('/activity/cardsAdd','Admin\ActivityController@cardsAdd');
Route::any('/activity/cardsEdit','Admin\ActivityController@cardsEdit');
Route::any('/activity/cardsWhiteListExport','Admin\ActivityController@cardsWhiteListExport');


Route::any('/cabinet/add','Admin\CabinetController@add');
Route::any('/cabinet/list','Admin\CabinetController@list');
Route::any('/cabinet/doorList','Admin\CabinetController@doorList');

Route::any('/replace/list','Admin\ReplaceController@list');

Route::any('/battery/list','Admin\BatteryController@list');
Route::any('/battery/add','Admin\BatteryController@add');


Route::any('/log/hostList','Admin\LogController@hostList');
Route::any('/log/slaveList','Admin\LogController@slaveList');
Route::any('/log/pluginList','Admin\LogController@pluginList');
Route::any('/log/portChange','Admin\LogController@portChange');
Route::any('/log/userEventLog','Admin\LogController@userEventLog');


Route::any('/test', function () {

    $a = public_path('demo/ttt.xls');

    Excel::load($a, function($reader) {
        /** @var \Maatwebsite\Excel\Readers\LaravelExcelReader $reader */
        $data = $reader->all()->toArray();
        var_dump($data);
        dd($data);exit;
    });
    exit;

    $cellData = [
        ['phone'],
        ['15921303355'],
        ['15921303357'],
        ['15921303358'],
        ['15921303359'],
    ];
    Excel::create('ttt',function($excel) use ($cellData){
        $excel->sheet('list', function($sheet) use ($cellData){
            $sheet->rows($cellData);
        });
    })->export('xls');
    exit;

    $d = Route::currentRouteAction();
    var_dump($d);
    exit;
    $a = view('admin/test');
    var_dump($a->render());
    //echo $a;
});

//放在最后
Route::any('/', 'Admin\AdminController@login')->name('login');
