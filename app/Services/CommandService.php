<?php

namespace App\Services;

use App\Models\ClientCommandLogs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CommandService extends BaseService
{

    const LIST_COMMAND_PRE = 'AxcCommandList_';

    const CMD_OPEN_BOX = 20001;//开箱
    const CMD_CLOSE_BOX = 20002;//关箱
    const CMD_START_CHARGE = 20003;//开始充电
    const CMD_STOP_CHARGE = 20004;//结束充电
    const CMD_SLAVE_UPGRADE = 23333;//从机升级
    const CMD_REMOTE_OPEN_TUNNEL = 24444;//开启远程隧道
    const CMD_REMOTE_CLOSE_TUNNEL = 25555;//关闭远程隧道

    /**
     * 下发命令
     */
    public static function send($deviceNo, $portNo, $cmd)
    {
        $deviceNo = intval($deviceNo);
        $number = self::_getServerNumber($deviceNo);

        $a = pack('P', $deviceNo);
        $b = pack('V', $portNo);
        $c = pack('V', $cmd);
        $val = $a . $b . $c;
        Log::debug("push redis deviceNo: $deviceNo, portNo: $portNo, cmd: $cmd");
        ClientCommandLogs::addLog($deviceNo, $portNo, $cmd);
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

    /**
     * 从机升级
     * @param $deviceNo
     * @return mixed
     */
    public static function sendSlaveUpgrade($deviceNo)
    {
        $cmd = self::CMD_SLAVE_UPGRADE;
        return self::send($deviceNo, 0, $cmd);
    }

    public static function sendOpenRemoteTunnel($deviceNo)
    {
        $cmd = self::CMD_REMOTE_OPEN_TUNNEL;
        return self::send($deviceNo, 0, $cmd);
    }

    public static function sendCloseRemoteTunnel($deviceNo)
    {
        $cmd = self::CMD_REMOTE_CLOSE_TUNNEL;
        return self::send($deviceNo, 0, $cmd);
    }

}