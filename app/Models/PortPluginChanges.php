<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PortPluginChanges
 *
 * @property int $id
 * @property int $device_id
 * @property int $port
 * @property int $pre_plugin 上次异物状态
 * @property int $plugin 当前异物状态
 * @property int $time_stamp 时间戳<设备上报的>
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortPluginChanges whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortPluginChanges whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortPluginChanges wherePlugin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortPluginChanges wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortPluginChanges wherePrePlugin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PortPluginChanges whereTimeStamp($value)
 * @mixin \Eloquent
 */
class PortPluginChanges extends Model
{


    protected $guarded = [];

    protected $table = 'port_plugin_change';

}
