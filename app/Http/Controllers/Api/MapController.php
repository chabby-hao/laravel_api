<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Libs\ErrorCode;
use App\Libs\Helper;
use App\Models\DeviceInfo;
use App\Models\WelfareCards;
use App\Models\WelfareUsers;
use App\Models\WelfareWhiteLists;
use App\Services\ActivityService;
use App\Services\DeviceService;
use App\Services\UserService;
use function Hprose\Future\error;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MapController extends Controller
{


    public function deviceData()
    {
        $datas = [
            [
                'device_no'=>'002100001',
                'value' => [
                    floatval(121.370286),
                    floatval(31.114697),
                    1,//数量
                ],
            ],
            [
                'device_no'=>'002100002',
                'value' => [
                    floatval(121.370142),
                    floatval(31.114655),
                    2,//数量
                ],
            ],
            [
                'device_no'=>'002100003',
                'value' => [//31.3258162649,121.4503672876
                    floatval(31.3258162649),
                    floatval(121.4503672876),
                    1,//数量
                ],
            ],
        ];

        foreach ($datas as &$data){
            $data['charging_nums'] = 0;
            $data['port_nums'] = 0;
            $deviceNo = $data['device_no'];
            $devices = DeviceInfo::whereDeviceNo($deviceNo)->get();
            if($devices){
                $data['port_nums'] = count($devices);
                foreach ($devices as $device){
                    $data['charging_nums'] += DeviceService::isCharging($deviceNo, $device->port_no) ? 1 : 0;
                    $data['address'] = $device->address;
                }
            }
        }

        return \response($datas, 200, ['Access-Control-Allow-Origin'=>'*']);
    }
}
