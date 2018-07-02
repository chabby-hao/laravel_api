<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ChargeNotifyLog
 *
 * @property int $id
 * @property string|null $device_no
 * @property int|null $port_no
 * @property int|null $type 0-正常充满，1=异常，10=开始充电
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeNotifyLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeNotifyLog whereDeviceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeNotifyLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeNotifyLog wherePortNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeNotifyLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeNotifyLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChargeNotifyLog extends Model
{

    protected $guarded = [];

    protected $table = 'charge_notify_log';

    public static function addLog($deviceNo, $portNo, $type)
    {
        $model = new self();
        $model->device_no = $deviceNo;
        $model->port_no = $portNo;
        $model->type = $type;
        return $model->save();
    }

}