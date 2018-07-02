<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SalvePortInfos
 *
 * @property int $udid 设备号
 * @property int $port 端口号
 * @property int $create_time 时间戳
 * @property int $sw_ver 从机版本
 * @property int $volt_input 输入电压
 * @property int $volt_output 输出电压
 * @property int $cur 电流
 * @property int $cap 电能
 * @property int $power 功率
 * @property int $power_on 打开/关闭电源配置
 * @property int $power_scale 功率因子
 * @property int $screw_status 丝杆状态
 * @property int $screw_on 丝杆配置
 * @property int $screw_pos 丝杆位置
 * @property int $rely_status 继电器开关状态
 * @property int $rely_alarm 继电器粘连告警
 * @property int $cur_alarm 过流保护告警
 * @property int $screw_alarm 丝杠不到位告警
 * @property int $loudian_alarml 漏电告警
 * @property int $port_usable 端口可用性
 * @property int $is_charge 是否在充电
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereCap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereCur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereCurAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereIsCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereLoudianAlarml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos wherePortUsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos wherePower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos wherePowerOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos wherePowerScale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereRelyAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereRelyStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereScrewAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereScrewOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereScrewPos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereScrewStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereSwVer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereUdid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereVoltInput($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalvePortInfos whereVoltOutput($value)
 * @mixin \Eloquent
 */
class SalvePortInfos extends Model
{


    protected $guarded = [];

    protected $table = 'salve_port_info';

}
