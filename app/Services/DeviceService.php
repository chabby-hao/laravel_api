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
        $val = Redis::hGet($key, 'relay_status');
        Log::debug("isPortUseFul deviceNo: $deviceNo, portno: $portNo, val: $val");
        return $val ? true : false;
    }

    public static function isCharging($deviceNo, $portNo)
    {
        $key = self::_getStatusKey($deviceNo, $portNo);
        $val = Redis::hGet($key, 'power_scale');
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