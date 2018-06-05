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
Route::any('/user/hasRefund', 'Api\UserController@hasRefund');
Route::any('/user/feedback', 'Api\UserController@feedback');
Route::any('/user/cardsList', 'Api\UserController@cardsList');
Route::any('/user/config', 'Api\UserController@config');
Route::any('/user/currentBattery', 'Api\UserController@currentBattery');


Route::any('/weixinPay/payJoinfee', 'Api\WeixinPayController@payJoinfee');
Route::any('/weixinPay/wxNotify', 'Api\WeixinPayController@wxNotify')->name('wxnotify');


Route::any('/charge/mapList', 'Api\ChargeController@mapList');
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

Route::any('/activity/isOpenPaySend', 'Api\ActivityController@isOpenPaySend');
Route::any('/activity/getCard', 'Api\ActivityController@getCard');

Route::any('/map/deviceData', 'Api\MapController@deviceData');

Route::any('replaceBattery/checkQrCode','Api\ReplaceBatteryController@checkQrCode');
Route::any('replaceBattery/appointment','Api\ReplaceBatteryController@appointment');
Route::any('replaceBattery/appointmentStatus','Api\ReplaceBatteryController@appointmentStatus');
Route::any('replaceBattery/cancelAppointment','Api\ReplaceBatteryController@cancelAppointment');
Route::any('replaceBattery/getAdress','Api\ReplaceBatteryController@getAdress');
Route::any('replaceBattery/getStep','Api\ReplaceBatteryController@getStep');
Route::any('replaceBattery/taskNotify','Api\ReplaceBatteryController@taskNotify');
Route::any('replaceBattery/list','Api\ReplaceBatteryController@list');
Route::any('replaceBattery/cabinetList','Api\ReplaceBatteryController@cabinetList');
Route::any('replaceBattery/getTaskToast','Api\ReplaceBatteryController@getTaskToast');
Route::any('replaceBattery/hasProcessingTask','Api\ReplaceBatteryController@hasProcessingTask');


Route::any('ops/startOps','Api\OpsController@startOps');
Route::any('ops/endOps','Api\OpsController@endOps');
Route::any('ops/opsInfo','Api\OpsController@opsInfo');






Route::any('cmd', function (Request $request) {
    $cmd = \App\Services\CommandService::CMD_START_CHARGE;
    $cmd = $request->input('cmd');//20003
    $deviceNo = $request->input('device_no');
    $portNo = $request->input('port_no');
    \App\Services\DeviceService::sendChargingHash($deviceNo, $portNo, mt_rand(10, 999));
    $a = \App\Services\CommandService::send($deviceNo, $portNo, $cmd);
    if ($a) {
        return \App\Libs\Helper::response();
    }
});

Route::get('redis', function (Request $request) {
    $key = $request->input('key');
    $a = explode(':', $key);
    $redisKey = \App\Services\DeviceService::KEY_HASH_STATUS_PRE . $a[0] . '_' . $a[1];
    $b = \Illuminate\Support\Facades\Redis::hGetAll($redisKey);


    if ($b) {
        //           volt_input	0.1V
        //volt_output	0.1V
        //cur	0.1A
        //cap	0.1KW
        //power 1W

        if (isset($b['volt_input'])) {
            $b['volt_input'] /= 10;
            $b['volt_input'] .= 'V';
        }
        if (isset($b['volt_output'])) {
            $b['volt_output'] /= 10;
            $b['volt_output'] .= 'V';
        }
        if (isset($b['cur'])) {
            $b['cur'] /= 100;
            $b['cur'] .= 'A';
        }
        if (isset($b['cap'])) {
            $b['cap'] /= 10;
            $b['cap'] .= 'KWH';
        }
        if (isset($b['power'])) {
            $b['power'] /= 10;
            $b['power'] .= 'W';
        }
        //  电表当前电量
        if (isset($b['ammeter_energy'])) {
            // KWH
            $b['ammeter_energy'] .= 'KWH';
        }

        //  电表当前电压
        if (isset($b['ammeter_volt'])) {
            // V
            $b['ammeter_volt'] .= 'V';
        }

        //  电表当前电流
        if (isset($b['ammeter_cur'])) {
            // A
            $b['ammeter_cur'] .= 'A';
        }

        //  电表当前功率
        if (isset($b['ammeter_power'])) {
            //W
            $b['ammeter_power'] .= 'W';
        }

        if (isset($b['battery_volt'])) {
            $b['battery_volt'] /= 1000;
            $b['battery_volt'] .= 'V';
        }

        if(isset($b['power_scale'])){
            $b['power_scale'] /= 1000;
        }

    }

    echo json_encode($b ?: []);
    exit;

});

Route::get('test', function () {
});

Route::get('phpinfo', function () {
    sleep(1);
    phpinfo();
});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
