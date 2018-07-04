<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PortStatueChanges
 *
 * @property int $id
 * @property int $device_id
 * @property int $port
 * @property int $state 当前可用状态
 * @property int $loudian_baohushixiao_alarm 漏电保护失效告警
 * @property int $screw_alarm 丝杆不到位告警
 * @property int $rely_alarm 继电器粘连告警
 * @property int $loudian_alarml 漏电告警
 * @property int $volt_input 输入电压
 * @property int $time_stamp 时间戳<设备上报的>
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortStatueChanges whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortStatueChanges whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortStatueChanges whereLoudianAlarml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortStatueChanges whereLoudianBaohushixiaoAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortStatueChanges wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortStatueChanges whereRelyAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortStatueChanges whereScrewAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortStatueChanges whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortStatueChanges whereTimeStamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortStatueChanges whereVoltInput($value)
 * @mixin \Eloquent
 */
class PortStatueChanges extends Model
{


    protected $guarded = [];

    protected $table = 'port_statue_change';

}
