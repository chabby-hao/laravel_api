<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ReplaceTasks
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $cabinet_id
 * @property int|null $door_id1
 * @property int|null $door_id2
 * @property int|null $state 0=初始化，10=未完成，20=已完成，30=异常
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property float|null $user_cost 应付
 * @property float|null $actual_cost 实付
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereActualCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereCabinetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereDoorId1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereDoorId2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereUserCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereUserId($value)
 * @mixin \Eloquent
 * @property int|null $step 0=扫码下发命令，10=放入旧电池，关闭柜门，20=放入新电池，关闭柜门
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereStep($value)
 * @property int|null $battery_id1
 * @property int|null $battery_id2
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereBatteryId1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplaceTasks whereBatteryId2($value)
 */
class ReplaceTasks extends Model
{

    //0=初始化，10=进行中，20=已完成，30=异常
    const TASK_STATE_INIT = 0;//初始化
    const TASK_STATE_TIMEOUT = 5;//命令超时
    const TASK_STATE_PROCESSING = 10;//命令已下达,进行中
    const TASK_STATE_COMPLETE = 20;//已完成
    const TASK_STATE_ABNORMAL = 30;//异常

    //$step 0=扫码下发命令，10=放入旧电池，关闭柜门，20=放入新电池，关闭柜门
    const STEP_INIT = 0;
    const STEP_10 = 10;
    const STEP_20 = 20;

    protected $table = 'replace_tasks';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public static function newTask($userId, $cabinetId)
    {

        $userDevice = UserDevice::whereUserId($userId)->first();
        if(!$userDevice){
            return false;
        }

        $model = new self();
        $model->user_id = $userId;
        $model->cabinet_id = $cabinetId;
        $model->battery_id1 = $userDevice->battery_id;
        $model->save();
        return $model;
    }

}
