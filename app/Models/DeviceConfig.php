<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DeviceConfig
 *
 * @mixin \Eloquent
 * @property string $device_no
 * @property float $proportion 分成比例
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceConfig whereDeviceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceConfig whereProportion($value)
 * @property float $univalence1 单价1
 * @property float $univalence2 单价2
 * @property int share_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceConfig whereUnivalence1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceConfig whereUnivalence2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceConfig whereUpdatedAt($value)
 */
class DeviceConfig extends Model
{

    const DEFAULT_UNIVALENCE = 1.2;//单价
    const DEFAULT_PROPORTION = 0.3;//分成比例
    const DETAULT_SHARE_TYPE = 0;//分成方式，默认收益凤城

    //表明
    protected $table = 'device_config';

    protected $primaryKey = 'device_no';

    protected $guarded = [];

    const SHARE_TYPE_INCOME = 0;//收益分成
    const SHARE_TYPE_COST = 1;// 消费分成

    public static function getShareTypeMap($type = null)
    {
        $map = [
            self::SHARE_TYPE_INCOME =>'收益分成',
            self::SHARE_TYPE_COST =>'消费分成',
        ];
        return $type === null ? $map : $map[$type];
    }

    /**
     * 获取充电棚单价
     * @param $deviceNo
     * @return float|mixed
     */
    public static function getUnivalence($deviceNo, $type = 1)
    {
        $row = self::whereDeviceNo($deviceNo)->first();
        $f = 'univalence' . $type;
        return $row && $row->$f ? $row->$f : self::DEFAULT_UNIVALENCE;
    }

    public static function getProportion($deviceNo)
    {
        $row = self::whereDeviceNo($deviceNo)->first();
        return $row && $row->proportion ? $row->proportion : self::DEFAULT_PROPORTION;
    }

    public static function getShareType($deviceNo)
    {
        $row = self::whereDeviceNo($deviceNo)->first();
        return $row && $row->share_type ? $row->share_type : self::DETAULT_SHARE_TYPE;
    }

}
