<?php

namespace App\Services;

use App\Libs\WxApi;
use App\Models\ChargeTasks;
use App\Models\DeviceInfo;
use App\Models\User;
use App\Models\WelfareUsers;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Psy\Command\WhereamiCommand;

class CabinetService extends BaseService
{

    /**
     * 柜子是否可用
     * @param $cabinetId
     */
    public static function isCabinetUseful($cabinetId)
    {
        //判断在线状态
    }

    /**
     * 判断柜子是否有可用的电池
     * @param $cabinetId
     * @param $batteryType
     */
    public static function hasAvailableBattery($cabinetId, $batteryType)
    {

    }

    /**
     * 下发换电指令
     * @param $cabinetNo
     */
    public static function sendReplaceCommand($cabinetNo, $taskId, $batteryId)
    {
        if(self::replaceRedisSet($cabinetNo, $taskId, $batteryId)){
            return CommandService::sendStartReplaceCmd($cabinetNo);
        }
        return false;
    }

    private function replaceRedisSet($cabinetNo, $taskId, $batteryId)
    {
        $key = 'repalce_' . $cabinetNo;
        $val1 = Redis::hSet($key, 'taskId', $taskId);
        $val2 = Redis::hSet($key, 'batteryId', $batteryId);
        Log::debug("replaceRedisSet, val: $val1,val2:$val2");
        return $val1 && $val2 ? true : false;
    }


}