<?php

namespace App\Services;

use App\Libs\ErrorCode;
use App\Models\ChargeTasks;
use App\Models\DeviceInfo;
use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Mixed_;

class ChargeService extends BaseService
{

    /**
     * 是否正在充电
     */
    public static function isChargeing()
    {

    }

    /**
     * 开始充电
     * @param $userId
     * @param $deviceNo
     * @param $portNo
     * @param $mode 0=充满（小时）
     */
    public static function startCharge($userId, $deviceId, $mode)
    {
        $duration = $mode * 3600;
        if (!$deviceModel = DeviceInfo::find($deviceId)) {
            Log::warning('deviceInfo not find deviceId:' . $deviceId);
            return false;
        }
        $deviceNo = $deviceModel->device_no;
        $portNo = $deviceModel->port_no;
        $chargeTaskMod = new ChargeTasks();
        $chargeTaskMod->createTask($userId, $deviceNo, $portNo, $duration);
        return CommandService::sendCommandChargeStart($deviceNo, $portNo);
    }

    /**
     * 结束充电
     * @param $device array|string    ['device_no'=>'123','port_no'=>'1']|$deviceId
     * @param int $state
     * @return bool
     */
    public static function endCharge($device, $state = ChargeTasks::TASK_STATE_COMPLETE, $sendCmd = true)
    {
        if(is_string($device)){
            if (!$deviceModel = DeviceInfo::find($device)) {
                Log::warning('deviceInfo not find deviceId:' . $device);
                return false;
            }
            $deviceNo = $deviceModel->device_no;
            $portNo = $deviceModel->port_no;
        }elseif(is_array($device)){
            $deviceNo = $device['device_no'];
            $portNo = $device['port_no'];
        }else{
            Log::error('device is not array|string');
            return false;
        }

        $model = ChargeTasks::where(['device_no' => $deviceNo, 'port_no' => $portNo])->orderBy('id', 'desc')->first();
        if (!$model) {
            return false;
        }
        $begin = $model->begin_at;
        $beginTime = strtotime($begin);
        $model->actual_time = time() - $beginTime;
        $model->task_state = $state;
        //此处预留扣费逻辑


        $model->save();
        if($sendCmd){
            CommandService::sendCommandChargeEnd($deviceNo, $portNo);
        }
    }

    /**
     * @param $deviceId
     * @return bool
     */
    public static function endChargeByUser($deviceId)
    {
        return self::endCharge($deviceId, ChargeTasks::TASK_STATE_USER_END);
    }

    /**
     * 时间结束自动中断停电
     * @param $deviceNo
     * @param $portNo
     * @return bool
     */
    public static function endChargeByTimeOver($deviceNo, $portNo)
    {
        return self::endCharge(['device_no'=>$deviceNo,'port_no'=>$portNo], ChargeTasks::TASK_STATE_TIME_END);
    }

    /**
     * @param $deviceNo
     * @param $portNo
     * @param $type 0 = 正常充满, 1 = 异常
     */
    public static function chargeHalt($deviceNo, $portNo, $type)
    {
        $model = ChargeTasks::where(['device_no' => $deviceNo, 'port_no' => $portNo])->orderBy('id', 'desc')->first();
        if (!$model) {
            return false;
        }

        if ($type == 0) {
            $state = ChargeTasks::TASK_STATE_COMPLETE;
        } else {
            $state = ChargeTasks::TASK_STATE_END_ABMORMAL;
        }

        $model->task_state = $state;
        return $model->save();
    }

    public static function chargeHaltComplete($device, $port)
    {

    }

    /**
     * 获取当前充电任务信息
     * @param $userId
     * @return array|bool
     */
    public static function getLastTaskInfo($userId)
    {
        $data = [
            'status' => 0,//初始化,正在冲
            'mins' => 0,//分钟
        ];
        $model = ChargeTasks::whereUserId($userId)->orderByDesc('id')->first();
        if (!$model) {
            return false;
        }

        $begin = strtotime($model->begin_at);
        $time = time() - $begin;
        $mins = floor($time / 60);
        $data['mins'] = sprintf('%02s', $mins);

        if ($model->task_state == ChargeTasks::TASK_STATE_END_ABMORMAL) {
            $data['status'] = 1;//异常终止
        } elseif ($model->task_state == ChargeTasks::TASK_STATE_TIME_END || $model->task_state == ChargeTasks::TASK_STATE_COMPLETE) {
            $data['status'] = 2;//充电完成
        }

        return $data;
    }

}