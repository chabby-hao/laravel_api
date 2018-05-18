<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Battery
 *
 * @property string|null $battery_id
 * @property string|null $udid
 * @property string|null $imei
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Battery whereBatteryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Battery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Battery whereUdid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Battery whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $battery_level 电池等级
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Battery whereBatteryLevel($value)
 */
class Battery extends Model
{

    protected $guarded = [];

    protected $table = 'battery';

    protected $primaryKey = 'battery_id';

    public $incrementing = false;

}