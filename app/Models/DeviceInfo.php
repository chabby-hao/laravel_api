<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DeviceInfo
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $device_no
 * @property int|null $port_no
 * @property string|null $url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereDeviceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo wherePortNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereUrl($value)
 * @property string|null $address 充电棚所在地址
 * @property int|null $qr_img
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereQrImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereUpdatedAt($value)
 */
class DeviceInfo extends Model
{
    //表明
    protected $table = 'device_info';

    protected $primaryKey = 'id';

    protected $guarded = [];

    /**
     * @return array
     */
    public static function getAllDeviceNo()
    {
        $m = self::select('device_no')->get()->unique('device_no');
        $devices = $m->toArray();
        foreach ($devices as &$device){
            $device = $device['device_no'];
        }
        return $devices;
    }

//    /**
//     * @param $id
//     * @return array
//     */
//    public static function getDeviceInfoById($id)
//    {
//        if($model = self::find($id)){
//            return ['device_no'=>$model->device_no,'port_no'=>$model->port_no];
//        }
//        return [];
//    }
}
