<?php

namespace App\Services;

use App\Models\ClientCommandLogs;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CommandService extends BaseService
{

    const LIST_COMMAND_PRE = 'AxcCommandList_';

    const LIST_REPLACE_COMMAND_PRE = 'HdgCommandList';

    const CMD_OPEN_BOX = 20001;//开箱
    const CMD_CLOSE_BOX = 20002;//关箱
    const CMD_START_CHARGE = 20003;//开始充电
    const CMD_STOP_CHARGE = 20004;//结束充电
    const CMD_SLAVE_UPGRADE = 23333;//从机升级
    const CMD_REMOTE_OPEN_TUNNEL = 24444;//开启远程隧道
    const CMD_REMOTE_CLOSE_TUNNEL = 25555;//关闭远程隧道


    const CMD_START_REPLACE = 30001;//开始换电
    const CMD_START_OPS = 30002;//运维
    const CMD_END_OPS = 30003;//结束维护

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

    public static function sendReplace($cabinetNo, $cmd)
    {
        $cabinetNo = intval($cabinetNo);
        $a = pack('P', $cabinetNo);
        $b = pack('V', $cmd);
        $val = $a . $b;
        Log::debug("push redis cabinetNo: $cabinetNo, cmd: $cmd");
        Redis::select(5);
        return Redis::lPush(self::LIST_REPLACE_COMMAND_PRE, $val);
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

    public static function sendStartReplaceCmd($cabinetNo)
    {
        $cmd = self::CMD_START_REPLACE;
        return self::sendReplace($cabinetNo, $cmd);
    }

    public static function sendStartOps($cabinetNo)
    {
        $cmd = self::CMD_START_OPS;
        return self::sendReplace($cabinetNo, $cmd);
    }

    public static function sendEndOps($cabinetNo)
    {
        $cmd = self::CMD_END_OPS;
        return self::sendReplace($cabinetNo, $cmd);
    }

    private static $channel = 'anqi';
    private static $secret = 'HhvjWsb6RkYKAvCdbXtvAraC';

    public static function sendApiCmd($udid, $ctl)
    {

        $url = 'http://api.vipcare.com/cloud/command';

        $data = [];
        $data['timestamp'] = time();
        $data['channel'] = self::$channel;
        $data['udid'] = $udid;
        $data['ctl'] = $ctl;
        ksort($data);

        $str = '';
        foreach ($data as $key => $value) {
            # code...
            $str .= $value;
        }
        $str .= self::$secret;

        $data['sign'] = md5($str);
        //var_dump($url . "?". http_build_query($data));

        $post = http_build_query($data);
        Log::debug("command api send : $post");

        $client = new Client();
        $r = $client->post($url, [
            'body' => $post,
            'headers'=>['Content-Type: application/x-www-form-urlencoded'],
        ]);

        $response = $r->getBody()->getContents();
        Log::debug('command api response: ' . $response);
        return $response;
    }

}