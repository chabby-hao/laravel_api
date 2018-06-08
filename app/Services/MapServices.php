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

    public static function getLocList($tencent = false)
    {
        $datas = [
            [
                'device_no' => '002100001',
                'value' => [
                    floatval(121.370286),
                    floatval(31.114697),
                ],
            ],
            [
                'device_no' => '002100002',
                'value' => [
                    floatval(121.370142),
                    floatval(31.114655),
                ],
            ],
            [
                'device_no' => '002100003',
                'value' => [//31.3258162649,121.4503672876
                    floatval(121.4508015117),
                    floatval(31.3262709538),
                ],
            ],
            [
                'device_no' => '002100004',
                'value' => [//31.3238490000,121.4521640000
                    floatval(121.4521640000),
                    floatval(31.3238490000),
                ],
            ],
            [
                'device_no' => '002100005',
                'value' => [//31.3236600000,121.4520520000
                    floatval(121.4520520000),
                    floatval(31.3236600000),
                ],
            ],

        ];
        if($tencent){
            //如果是腾讯要转一下
            foreach ($datas as $k => $data){
                $loc = self::ConvertBD09ToGCJ02($data['value'][1], $data['value'][0]);
                $datas[$k]['value'] = [$loc['lng'], $loc['lat']];
            }
        }
        return $datas;
    }

    /**
     * 百度地图BD09坐标---->中国正常GCJ02坐标
     * 腾讯地图用的也是GCJ02坐标
     * @param double $lat 纬度
     * @param double $lng 经度
     * @return array();
     */
    public static function ConvertBD09ToGCJ02($lat, $lng)
    {
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta);
        $lat = $z * sin($theta);
        return array('lng' => $lng, 'lat' => $lat);
    }
}