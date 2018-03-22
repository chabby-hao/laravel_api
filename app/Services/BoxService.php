<?php

namespace App\Services;

use App\Models\DeviceInfo;
use App\Models\Orders;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class BoxService extends BaseService
{

    /**
     * 检测箱子是否打开
     * @param $deviceNo
     * @param $portNo
     * @return bool
     */
    public static function getBoxStatus($deviceNo, $portNo)
    {
        return DeviceService::getBoxStatus($deviceNo, $portNo);
    }

    public static function getBoxStatusById($deviceId)
    {
        $deviceInfo = DeviceService::getDeviceInfo($deviceId);
        return self::getBoxStatus($deviceInfo['deviceNo'], $deviceInfo['portNo']);
    }

    /**
     * 打开箱子
     */
    public static function openBox($deviceNo, $portNo)
    {
        //下发开箱命令，如果不是开箱
        if(self::getBoxStatus($deviceNo, $portNo) != 1){
            CommandService::sendCommandBoxOpen($deviceNo, $portNo);
        }
    }

    public static function closeBox($deviceNo, $portNo)
    {
        if(self::getBoxStatus($deviceNo, $portNo) != 0){
            CommandService::sendCommandBoxClose($deviceNo, $portNo);
        }
    }
}