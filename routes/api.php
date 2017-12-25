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

Route::middlewareGroup('api', ['token_login']);

Route::any('/user/loginByCode', 'Api\UserController@loginByCode');
Route::any('/user/bindPhoneForWx', 'Api\UserController@bindPhoneForWx');
Route::any('/user/login', 'Api\UserController@login');
Route::any('/user/checkToken', 'Api\UserController@checkToken');
Route::any('/user/sendMsgCode', 'Api\UserController@sendMsgCode');
Route::any('/user/balance', 'Api\UserController@balance');
Route::any('/user/refund', 'Api\UserController@refund');


Route::any('/weixinPay/payJoinfee', 'Api\WeixinPayController@payJoinfee');
Route::any('/weixinPay/wxNotify', 'Api\WeixinPayController@wxNotify')->name('wxnotify');


Route::any('/charge/openBox', 'Api\ChargeController@openBox');
Route::any('/charge/chargeBegin', 'Api\ChargeController@chargeBegin');
Route::any('/charge/chargeEnd', 'Api\ChargeController@chargeEnd');
Route::any('/charge/chargeHalt', 'Api\ChargeController@chargeHalt');
Route::any('/charge/powerOn', 'Api\ChargeController@powerOn');
Route::any('/charge/taskId', 'Api\ChargeController@taskId');
Route::any('/charge/lists', 'Api\ChargeController@lists');
Route::any('/charge/checkQrCode', 'Api\ChargeController@checkQrCode');
Route::any('/charge/deviceAddress', 'Api\ChargeController@deviceAddress');
Route::any('/charge/chargingTime', 'Api\ChargeController@chargingTime');
Route::any('/charge/chargeMode', 'Api\ChargeController@chargeMode');
Route::any('/charge/lastFinish', 'Api\ChargeController@lastFinish');

Route::any('/orders/lists', 'Api\OrdersController@lists');

Route::any('cmd',function (Request $request){
    $cmd = \App\Services\CommandService::CMD_START_CHARGE;
    $cmd = $request->input('cmd');//20003
    $deviceNo = $request->input('device_no');
    $portNo = $request->input('port_no');
    \App\Services\DeviceService::sendChargingHash($deviceNo, $portNo, mt_rand(10,999));
    $a = \App\Services\CommandService::send($deviceNo, $portNo,$cmd);
    if($a){
        return \App\Libs\Helper::response();
    }
});

Route::get('test', function () {

    $a = \App\Models\User::charging(8,0.01);
    var_dump($a);
    exit;

    $c = new \App\Libs\WxApi();

    $b = $c->sendMessage('{
  "touser": "oQVwG0YmO8gHFIFUA0KHmcXCcYw4",  
  "template_id": "8ySltzpdZn80ymIGD6-2N6vhQ1YFGbjRMZ0v8js22YA", 
  "page": "pages/charge_ab/charge_ab",          
  "form_id": "4b30c5ecb2a9bddc2f9f16b68e46b8ac"
}');
    exit;

    $a = \App\Services\CommandService::sendCommandChargeStart('99981469', '1');
    var_dump($a);
    exit;

    $a = \App\Models\DeviceInfo::find(1);
    var_dump($a);
    exit;

    \App\Services\BoxService::isOpen(1, 2);

//    $c =route('wxnotify');
//    var_dump($c);exit;
    $b = \App\Services\UserService::addUserBalance(4, 0.01);
    var_dump($b);
    //\Illuminate\Support\Facades\Log::info('tttt');exit;
});

Route::get('phpinfo', function () {
    phpinfo();
});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
