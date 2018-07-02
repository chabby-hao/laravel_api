<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ReplaceNotifyLog
 *
 * @property int $id
 * @property int|null $task_id
 * @property string|null $cabinet_no
 * @property int|null $step
 * @property string|null $battery_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceNotifyLog whereBatteryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceNotifyLog whereCabinetNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceNotifyLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceNotifyLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceNotifyLog whereStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceNotifyLog whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceNotifyLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReplaceNotifyLog extends Model
{

    protected $guarded = [];

    protected $table = 'replace_notify_log';


}