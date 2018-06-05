<?php
/**
 * Created by PhpStorm.
 * User: chabby
 * Date: 2017/11/7
 * Time: 上午11:04
 */

namespace App\Services;

use App\Models\VerifyCode;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;

class MapServices extends BaseService
{
    
    public static function getLocList()
    {
        $datas = [
            [
                'device_no'=>'002100001',
                'value' => [
                    floatval(121.370286),
                    floatval(31.114697),
                ],
            ],
            [
                'device_no'=>'002100002',
                'value' => [
                    floatval(121.370142),
                    floatval(31.114655),
                ],
            ],
            [
                'device_no'=>'002100003',
                'value' => [//31.3258162649,121.4503672876
                    floatval(121.4508015117),
                    floatval(31.3262709538),
                ],
            ],
            [
                'device_no'=>'002100004',
                'value' => [//31.3258162649,121.4503672876
                    floatval(121.4523635039),
                    floatval(31.3241817395),
                ],
            ],
            [
                'device_no'=>'002100005',
                'value' => [//31.3258162649,121.4503672876
                    floatval(121.4523259530),
                    floatval(31.3240717519),
                ],
            ],

        ];
        return $datas;
    }

}