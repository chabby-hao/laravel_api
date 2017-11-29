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
 */
class DeviceInfo extends Model
{
    //表明
    protected $table = 'device_info';

    protected $primaryKey = 'id';

    protected $fillable = ['id','device_no'];

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
