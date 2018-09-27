<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DeviceCostDetail
 *
 * @property int $id
 * @property string|null $date Y-m-d
 * @property string $device_no
 * @property float $shared_amount 分成
 * @property float|null $device_cost_amount 设备花费
 * @property float|null $user_cost_amount 用户流水
 * @property float|null $charge_times
 * @property float|null $electric_quantity
 * @property float|null $charge_duration
 * @property float|null $user_count
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceCostDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceCostDetail whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceCostDetail whereDeviceCostAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceCostDetail whereDeviceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceCostDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceCostDetail whereSharedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceCostDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceCostDetail whereUserCostAmount($value)
 * @mixin \Eloquent
 */
class DeviceCostDetail extends Model
{


    //表明
    protected $table = 'device_cost_detail';

    protected $guarded = [];


}
