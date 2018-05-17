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

class BatteryService extends BaseService
{

    const BATTERY_STATE_UNUSEFUL = 0;//不可用
    const BATTERY_STATE_USEFUL = 1;//可用
    const BATTERY_STATE_USING = 2;//使用中
    const BATTERY_STATE_OPS = 3;//维护中

    public static function getBatteryInfo($batteryId)
    {
        Redis::select(5);
        $key = 'bat:' . $batteryId;
        $data = Redis::hGetAll($key);
        Log::debug("batteryInfo key: $key", $data);
        return $data;
    }

    public static function getZhangfeiByBatteryId($batteryId)
    {
        Redis::select(1);
        $key = 'zhangfei_charge_batteryID:' . $batteryId;
        $data = Redis::hGetAll($key) ?: [];
        //Log::debug("redis hgetall $key", $data);
        /*$ret['udid'] = strval($udid);
        $ret["timeStamp"] = intval($redisData["timeStamp"]);
        $ret["type"] = intval($redisData["type"]);
        $ret["batteryOnlieState"] = intval($redisData["batteryOnlieState"]);
        $ret["lineState"] = intval($redisData["lineState"]);
        $ret["batteryID"] = strval($redisData["batteryID"]);
        $ret["batteryLevel"] = intval($redisData["batteryLevel"]);
        $ret["batteryVoltage"] = intval($redisData["batteryVoltage"]);
        $ret["coreTemperature"] = intval($redisData["coreTemperature"]);
        $ret["batteryCycleTimes"] = intval($redisData["batteryCycleTimes"]);
        $ret["batteryIOCurrent"] = intval($redisData["batteryIOCurrentPlus"]);
        if(!$ret['batteryIOCurrent']){
            $ret['batteryIOCurrent'] = intval($redisData['batteryIOCurrent']);
        }
        $ret["PCBTemperature"] = intval($redisData["PCBTemperature"]);
        $ret["batteryHealthState"] = intval($redisData["batteryHealthState"]);
        $ret["batteryIOState"] = intval($redisData["batteryIOState"]);*/
        return $data;
    }

    public static function closeBatteryOutput($udid)
    {
        $ctl = 'zfcd_b_lock';
        return CommandService::sendApiCmd($udid, $ctl);
    }

    public static function openBatteryOutput($udid)
    {
        $ctl = 'zfcd_b_unlock';
        return CommandService::sendApiCmd($udid, $ctl);
    }

}