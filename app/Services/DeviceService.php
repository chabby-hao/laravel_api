<?php

namespace App\Services;

use App\Libs\Helper;
use App\Libs\QrApi;
use App\Libs\WxApi;
use App\Models\DeviceInfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Psy\Command\Command;

class DeviceService extends BaseService
{

    //发命令的时候 device_no 是 long 型的

    const KEY_HASH_STATUS_PRE = 'axcPortInfo_';
    const KEY_HASH_SEND_PRE = 'axcSendInfo_';

    public static function getDeviceId($deviceNo, $portNo)
    {
        $model = DeviceInfo::where(['device_no' => $deviceNo, 'port_no' => $portNo])->first();
        return $model ? $model->id : false;
    }

    public static function getDeviceIdByUrl($url)
    {
        $model = DeviceInfo::whereUrl($url)->first();
        return $model ? $model->id : false;
    }

    /**
     * 获取['device_no'=>$model->device_no,'port_no'=>$model->port_no]
     * @param $deviceId
     * @return array
     */
    public static function getDeviceInfo($deviceId)
    {
        $model = DeviceInfo::find($deviceId);
        return $model ? ['device_no' => $model->device_no, 'port_no' => $model->port_no, 'address' => $model->address] : [];
    }

    /**
     * 设备是否在线
     * @param $deviceNo
     * @return bool
     */
    public static function isDeviceOnline($deviceNo)
    {
        $key = self::KEY_HASH_STATUS_PRE . intval($deviceNo) . '_0';
        $val = Redis::hGet($key, 'attach');
        Log::debug("isDeviceOnline deviceNo: $deviceNo attach: $val");
        return $val ? true : false;
    }

    /**
     * 检查端口是否可用
     * @param $deviceNo
     * @param $portNo
     * @return bool
     */
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
    public static function isChargeCmdSendOk($deviceNo, $portNo)
    {
        $key = self::_getStatusKey($deviceNo, $portNo);
        $val = Redis::hGet($key, 'rely_status');
        return $val ? true : false;
    }

    /**
     * 是否正在充电
     * @param $deviceNo
     * @param $portNo
     * @return bool
     */
    public static function isCharging($deviceNo, $portNo)
    {
        $key = self::_getStatusKey($deviceNo, $portNo);
        $val = Redis::hGet($key, 'is_charge');
        return $val ? true : false;
    }

    public static function getBoxStatus($deviceNo, $portNo)
    {
        $key = self::_getStatusKey($deviceNo, $portNo);
        $val = Redis::hGet($key, 'screw_status');
        Log::debug('box open status : ' . $val);
        return $val;
    }

    private static function _getStatusKey($deviceNo, $portNo)
    {
        $deviceNo = intval($deviceNo);
        $key = self::KEY_HASH_STATUS_PRE . $deviceNo . '_' . $portNo;
        return $key;
    }

    private static function _getSendKey($deviceNo, $portNo)
    {
        $deviceNo = intval($deviceNo);
        $key = self::KEY_HASH_SEND_PRE . $deviceNo . '_' . $portNo;
        return $key;
    }

    public static function sendChargingHash($deviceNo, $portNo, $taskId)
    {
        $deviceNo = intval($deviceNo);
        $key = self::_getSendKey($deviceNo, $portNo);
        return Redis::hSet($key, 'task_id', $taskId);
    }

    public static function slaveUpgrade($deviceNo, $url, $version)
    {
        $deviceNo = intval($deviceNo);
        $key = self::_getSendKey($deviceNo, 0);
        $data = [
            'upgrade_slave_url' => $url,
            'upgrade_slave_version' => $version,
        ];
        Redis::hMSet($key, $data);
        return CommandService::sendSlaveUpgrade($deviceNo);
    }

    public static function openRemoteTunnel($deviceNo, $portNo, $userUrl)
    {
        $deviceNo = intval($deviceNo);
        $key = self::_getSendKey($deviceNo, 0);
        $data = [
            'ssh_user@url' => $userUrl,
            'ssh_usable_port' => $portNo,
        ];
        Redis::hMSet($key, $data);
        Log::debug('redis key ' . $key . ' set data :' . json_encode($data));
        $res = CommandService::sendOpenRemoteTunnel($deviceNo);
        Log::debug('push redis res : ' . $res);
        return $res;
    }

    public static function closeRemoteTunnel($deviceNo)
    {
        return CommandService::sendCloseRemoteTunnel($deviceNo);
    }

    public static function addDevice($data)
    {
        $model = DeviceInfo::whereDeviceNo($data['device_no'])->wherePortNo($data['port_no'])->first();
        if ($model) {
            return false;
        }
        try {
            $model = DeviceInfo::create($data);
            if ($model) {
                $deviceId = $model->id;
                $wxApi = new WxApi();
                $qrInfo = $wxApi->getQrImg($deviceId);
                $model->qr_img = $qrInfo['img_url'];
                $url = Helper::getQrUrl($qrInfo['img_path']);
                $model->url = $url; //return decoded text from QR Code
                return $model->save();
            }
        } catch (\Exception $e) {
            Log::error('exception when add device :' . $e->getMessage());
        }
        return false;
    }

    public static function isOldDevice($deviceNo)
    {
        //兼容老固件，所有关闭都发命令
        if (in_array(intval($deviceNo), [2100001, 2100002])) {
            return true;
        }
        return false;
    }

}