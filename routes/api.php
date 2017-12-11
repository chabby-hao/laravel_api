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

//Route::any('/user/checkLogin','Api\UserController@checkLogin');

Route::any('/user/bindPhoneForWx', 'Api\UserController@bindPhoneForWx');

Route::any('/user/login', 'Api\UserController@login');

Route::any('/user/checkToken', 'Api\UserController@checkToken');

Route::any('/user/sendMsgCode', 'Api\UserController@sendMsgCode');

Route::any('/user/balance', 'Api\UserController@balance');

Route::any('/weixinPay/payJoinfee', 'Api\WeixinPayController@payJoinfee');

Route::any('/weixinPay/wxNotify', 'Api\WeixinPayController@wxNotify')->name('wxnotify');

Route::any('/charge/openBox', 'Api\ChargeController@openBox');

Route::any('/charge/chargeBegin', 'Api\ChargeController@chargeBegin');

Route::any('/charge/chargeEnd', 'Api\ChargeController@chargeEnd');
Route::any('/charge/chargeHalt', 'Api\ChargeController@chargeHalt');
Route::any('/charge/powerOn', 'Api\ChargeController@powerOn');
Route::any('/charge/taskId', 'Api\ChargeController@taskId');

Route::any('/charge/chargingTime', 'Api\ChargeController@chargingTime');

Route::any('/orders/lists', 'Api\OrdersController@lists');

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
