<?php

namespace App\Http\Controllers\Api;

use App\Libs\ErrorCode;
use App\Libs\Helper;
use App\Models\ChargeTasks;
use App\Models\DeviceInfo;
use App\Services\BoxService;
use App\Services\ChargeService;
use App\Services\DeviceService;
use App\Services\RequestService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ChargeController extends Controller
{

    /**
     * 打开盒子
     * @param Request $request
     */
//    public function openBox(Request $request)
//    {
//        if (!BoxService::isOpen()) {
//            BoxService::openBox();
//            return $this->responseOk();
//        }
//    }

    public function deviceAddress(Request $request)
    {
        $deviceId = $request->input('device_id');
        $deviceInfo = DeviceService::getDeviceInfo($deviceId);
        $data = [
            'address' => $deviceInfo['address'],
            'address_number' => '编号：' . $deviceInfo['device_no'] . '-' . $deviceInfo['port_no']
        ];
        return Helper::response($data);
    }

    /**
     * 检查二维码是否可用,并返回可用的设备id
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function checkQrCode(Request $request)
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }
        //二维码扫描结果
        $url = $request->input('url');
        //二维码是否有效
        if (!$deviceId = DeviceService::getDeviceIdByUrl($url)) {
            return Helper::responeseError(ErrorCode::$qrCodeNotFind);
        }
        //设备是否在线
        $deviceInfo = DeviceService::getDeviceInfo($deviceId);
        $deviceNo = $deviceInfo['device_no'];
        $portNo = $deviceInfo['port_no'];
        if(!DeviceService::isDeviceOnline($deviceNo)){
            return Helper::responeseError(ErrorCode::$deviceNotOnline);
        }
        //检查设备端口是否可用
        if(DeviceService::isPortUseful($deviceNo, $portNo)){
            return Helper::responeseError(ErrorCode::$deviceNotUseful);
        }

        if(UserService::getUserBalance($userId) <= 0){
            return Helper::responeseError(ErrorCode::$balanceNotEnough);
        }

        if(DeviceService::isCharging($deviceNo, $portNo)){
            return Helper::responeseError(ErrorCode::$isChargingNow);
        }

        return Helper::response(['device_id' => $deviceId, 'address' => $deviceInfo['address']]);
    }

    /**
     *  用户点击充电按钮开始充电
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function chargeBegin(Request $request)
    {
        $post = $request->post();
        $mode = $post['mode']; //0=充满模式,其余为多少小时
        $deviceId = $post['device_id'];//设备id
        $formId = $post['form_id'];

        Log::debug('chargeBegin :' . json_encode($post));

        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        //充电
        if (!$taskId = ChargeService::startCharge($userId, $deviceId, $mode, $formId)) {
            return Helper::responeseError(ErrorCode::$qrCodeNotFind);
        }

        return Helper::response(['task_id'=>$taskId]);
    }

    /**
     * 结束充电(用户主动调用)
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function chargeEnd(Request $request)
    {
        $taskId = $request->input('task_id');

        $device = ChargeTasks::find($taskId);
        if($device){
            ChargeService::endChargeByUser(['device_no'=>$device->device_no,'port_no'=>$device->port_no]);
        }


        return $this->responseOk();
    }

    /**
     * 充电停止推送（python调用）
     * @param Request $request
     */
    public function chargeHalt(Request $request)
    {
        $inputRequire = ['device_no', 'port_no', 'type', 'timestamp', 'sign'];
        $input = $this->checkRequireParams($inputRequire, $request->input());
        if ($input instanceof Response) {
            return $input;
        }

        if (!RequestService::checkSign($input)) {
            return Helper::responeseError(ErrorCode::$errSign);
        }

        $deviceNo = $input['device_no'];
        $portNo = $input['port_no'];
        $type = $input['type'];

        Log::debug('chargeHalt receive data: ' . json_encode($request->input()));

        //$type 0 = 正常充满, 1 = 异常
        if ($type == 0) {
            ChargeService::chargeHaltComplete($deviceNo, $portNo);
        } else {
            ChargeService::chargeHaltAbnormal($deviceNo, $portNo);
        }

        return Helper::response([
            'device_no' => $deviceNo,
            'port_no' => $portNo,
            'type' => $type,
        ]);
    }

    /**
     * Python调用，设备开始通电
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function powerOn(Request $request)
    {
        $inputRequired = ['task_id', 'timestamp', 'sign'];
        $input = $this->checkRequireParams($inputRequired, $request->input());

        if ($input instanceof Response) {
            return $input;
        }

        if (!RequestService::checkSign($input)) {
            return Helper::responeseError(ErrorCode::$errSign);
        }

        $taskId = $input['task_id'];

        ChargeService::beginCharingByTaskId($taskId);

        Log::debug('power is on .' . json_encode($request->input()));

        return Helper::response([
            'task_id' => $taskId,
        ]);
    }

    /**
     * 获取充电时间
     * @param Request $request
     */
    public function chargingTime()
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }
        if (!$data = ChargeService::getLastTaskInfo($userId)) {
            return Helper::responeseError(ErrorCode::$chargeTaskNotFind);
        }

        return Helper::response($data);
    }

    /**
     * 获取充电模式文本
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function chargeMode()
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }
        if (!$model = ChargeTasks::getLastTaskByUserId($userId)) {
            return Helper::responeseError(ErrorCode::$chargeTaskNotFind);
        }
        $expectTime = $model->expect_time;
        $expectHour = round($expectTime / 3600);
        if ($expectHour == 0) {
            $mode = '您已选择充满模式';
        } else {
            $mode = '您选择充' . $expectHour . '小时';
        }
        $data = [
            'mode_text' => $mode,
            'price_text' => '按充电时间计费：' . ChargeService::PER_MINUTE_CHARGE_PRICE . '元/分钟',
        ];
        return Helper::response($data);
    }

    /**
     * 获取充电结束提示文本
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function lastFinish()
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }
        if (!$model = ChargeTasks::getLastTaskByUserId($userId)) {
            return Helper::responeseError(ErrorCode::$chargeTaskNotFind);
        }
        if (!in_array($model->task_state, ChargeTasks::getFinishStateMap())) {
            return Helper::responeseError(ErrorCode::$chargeNotFinishYet);
        }
        $mins = floor($model->actual_time / 60);
        $costs = $model->user_cost;
        $data = [
            'content' => '充电' . $mins . '分钟，花费' . $costs . '元。',
        ];
        return Helper::response($data);
    }

    /**
     * 获取当前充电任务
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function taskId()
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        $taskId = ChargeService::getUnfinishTaskIdByUserId($userId);

        $data = ['task_id' => intval($taskId)];
        return Helper::response($data);

    }

    public function lists()
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        $list = ChargeService::chargeList($userId);

        return Helper::response($list);

    }
}
