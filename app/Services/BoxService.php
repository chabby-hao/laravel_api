<?php
namespace App\Services;

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
     */
    public static function isOpen()
    {
        $a = Redis::hGet('xx','xxx');
        var_dump($a);
    }

    /**
     * 打开箱子
     */
    public static function openBox()
    {

    }

}