<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Libs\ErrorCode;
use App\Libs\Helper;
use App\Models\WelfareCards;
use App\Models\WelfareUsers;
use App\Models\WelfareWhiteLists;
use App\Services\ActivityService;
use App\Services\UserService;
use function Hprose\Future\error;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MapController extends Controller
{


    public function deviceData()
    {
        $data = [
            [
                'device_no'=>'1111111',
                'address'=>'万和家园',
                'device_num'=>2,
                'port_nums'=>5,
                'charging_nums'=>0,
                'value' => [
                    floatval(72.1),
                    floatval(31.2),
                    1,//数量
                ],
            ],
            [
                'device_no'=>'1111111',
                'address'=>'万和家园',
                'device_num'=>1,
                'port_nums'=>5,
                'charging_nums'=>0,
                'value' => [
                    floatval(72.1),
                    floatval(32.2),
                    2,//数量
                ],
            ],
        ];
        return \response($data);
    }
}
