<?php
namespace App\Services;

use App\Models\DeviceInfo;
use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class BoxService extends  BaseService
{

    /**
     * 检测箱子是否打开
     * @param $deviceNo
     * @param $portNo
     * @return bool
     */
    public static function isOpen($deviceNo, $portNo)
    {
        return DeviceService::isBoxOpen($deviceNo, $portNo);
    }

    /**
     * 打开箱子
     */
    public static function openBox($deviceNo, $portNo)
    {
        //下发开箱命令
        CommandService::sendCommandBoxOpen($deviceNo, $portNo);
    }

}