<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class DeviceService extends BaseService
{

    const KEY_HASH_STATUS_PRE = 'axcPortInfo_';

    public static function isPortUseful($deviceNo, $portNo)
    {
        $key = self::_getStatusKey($deviceNo, $portNo);
        $val = Redis::hGet($key, 'usable');
        Log::debug("isPortUseFul deviceNo: $deviceNo, portno: $portNo, val: $val");
        return $val ? true : false;
    }

    /**
     * 充电是否发送成功
     * @param $deviceNo
     * @param $portNo
     * @return bool
     */
    public static function isChargSendOk($deviceNo, $portNo)
    {
        $key = self::_getStatusKey($deviceNo, $portNo);
        $val = Redis::hGet($key, 'rely_status');
        return $val ? true : false;
    }

    public static function isCharging($deviceNo, $portNo)
    {
        $key = self::_getStatusKey($deviceNo, $portNo);
        $val = Redis::hGet($key, 'is_charge');
        return $val ? true : false;
    }

    public static function isBoxOpen($deviceNo, $portNo)
    {
        $key = self::_getStatusKey($deviceNo, $portNo);
        $val = Redis::hGet($key, 'screw_status');
        return $val ? true : false;
    }

    private static function _getStatusKey($deviceNo, $portNo)
    {
        $key = self::KEY_HASH_STATUS_PRE . $deviceNo . '_' . $portNo;
        return $key;
    }


}