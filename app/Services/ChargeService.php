<?php
namespace App\Services;

use App\Libs\ErrorCode;
use App\Models\ChargeTasks;
use App\Models\DeviceInfo;
use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        if(!$deviceModel = DeviceInfo::find($deviceId)){
            Log::warning('deviceInfo not find deviceId:' . $deviceId);
            return false;
        }
        $deviceNo = $deviceModel->device_no;
        $portNo = $deviceModel->port_no;
        $chargeTaskMod = new ChargeTasks();
        $chargeTaskMod->createTask($userId, $deviceNo, $portNo, $duration);
        CommandService::sendCommandChargeStart($deviceNo, $portNo);
    }

    /**
     * 结束充电
     */
    public static function endCharge($deviceId, $state = ChargeTasks::TASK_STATE_COMPLETE)
    {
        if(!$deviceModel = DeviceInfo::find($deviceId)){
            Log::warning('deviceInfo not find deviceId:' . $deviceId);
            return false;
        }
        $deviceNo = $deviceModel->device_no;
        $portNo = $deviceModel->port_no;
        $model = ChargeTasks::where(['device_no'=>$deviceNo,'port_no'=>$portNo])->orderBy('id','desc')->first();
        if(!$model){
            return false;
        }
        $begin = $model->begin_at;
        $beginTime = strtotime($begin);
        $model->actual_time = time() - $beginTime;
        $model->task_state = $state;
        //此处预留扣费逻辑

        return $model->save();
    }

    public static function endChargeByUser($deviceId)
    {
        return self::endCharge($deviceId, ChargeTasks::TASK_STATE_COMPLETE);
    }

}