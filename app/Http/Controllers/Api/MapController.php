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
                'address'=>'万和家园',
                'device_no'=>'20000001',
                'port_nums'=>5,
                'charging_nums'=>0,
                'value' => [
                    floatval(121.370286),
                    floatval(31.114697),
                    1,//数量
                ],
            ],
            [
                'address'=>'万和家园',
                'device_no'=>'20000002',
                'port_nums'=>5,
                'charging_nums'=>0,
                'value' => [
                    floatval(121.370142),
                    floatval(31.114655),
                    2,//数量
                ],
            ],
        ];
        return \response($data, 200, ['Access-Control-Allow-Origin'=>'*']);
    }
}
