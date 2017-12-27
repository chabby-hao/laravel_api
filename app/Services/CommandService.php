<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CommandService extends BaseService
{

    const LIST_COMMAND_PRE = 'AxcCommandList_';

    const CMD_OPEN_BOX = 20001;//开箱
    const CMD_CLOSE_BOX = 20002;//关箱
    const CMD_START_CHARGE = 20003;//开始充电
    const CMD_STOP_CHARGE = 20004;//结束充电

    /**
     * 下发命令
     */
    public static function send($deviceNo, $portNo, $cmd)
    {
        $number = self::_getServerNumber($deviceNo);

        $a = pack('P', $deviceNo);
        $b = pack('V', $portNo);
        $c = pack('V', $cmd);
        $val = $a . $b . $c;
        Log::debug("push redis deviceNo: $deviceNo, portNo: $portNo, cmd: $cmd");
        return Redis::lPush(self::LIST_COMMAND_PRE . $number, $val);
    }

    /**
     * @param $deviceNo
     * @return int
     */
    private static function _getServerNumber($deviceNo)
    {
        $number = Redis::hGet('axc_device_server', $deviceNo);
        return $number ? $number - 1 : 0;
    }

    /**
     * 下发开箱命令
     */
    public static function sendCommandBoxOpen($deviceNo, $portNo)
    {
        $cmd = self::CMD_OPEN_BOX;
        return self::send($deviceNo, $portNo, $cmd);
    }

    public static function sendCommandBoxClose($deviceNo, $portNo)
    {
        $cmd = self::CMD_CLOSE_BOX;
        return self::send($deviceNo, $portNo, $cmd);
    }

    /**
     * 开始充电
     */
    public static function sendCommandChargeStart($deviceNo, $portNo)
    {

        $cmd = self::CMD_START_CHARGE;
        return self::send($deviceNo, $portNo, $cmd);
    }

    /**
     * 结束充电
     */
    public static function sendCommandChargeEnd($deviceNo, $portNo)
    {
        $cmd = self::CMD_STOP_CHARGE;
        return self::send($deviceNo, $portNo, $cmd);
    }


}