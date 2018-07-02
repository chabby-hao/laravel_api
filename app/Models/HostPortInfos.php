<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\HostPortInfos
 *
 * @property int $udid 设备号
 * @property int $port 端口号
 * @property int $create_time 时间戳
 * @property int $node_rely_status1 干接点继电器状态1
 * @property int $node_rely_status2 干接点继电器状态2
 * @property float $ammeter_energy 电表当前电量
 * @property float $ammeter_volt 电能
 * @property float $ammeter_cur 电表当前电压
 * @property float $ammeter_power 电表当前电流
 * @property float $ammeter_power_scale 电表当前功率因素
 * @property float $battery_volt 备用电池电量
 * @property int $port_usable 端口可用性
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HostPortInfos whereAmmeterCur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HostPortInfos whereAmmeterEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HostPortInfos whereAmmeterPower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HostPortInfos whereAmmeterPowerScale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HostPortInfos whereAmmeterVolt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HostPortInfos whereBatteryVolt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HostPortInfos whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HostPortInfos whereNodeRelyStatus1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HostPortInfos whereNodeRelyStatus2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HostPortInfos wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HostPortInfos wherePortUsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HostPortInfos whereUdid($value)
 * @mixin \Eloquent
 */
class HostPortInfos extends Model
{


    protected $guarded = [];

    protected $table = 'host_port_info';

}
