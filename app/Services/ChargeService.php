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
    public static function endCharge()
    {

    }

}