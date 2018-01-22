<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ChargeTasks
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $expect_time 用户预计充电时长(秒)，0-充满
 * @property int|null $actual_time 实际充电时长
 * @property int|null $begin_at 开始时间
 * @property int|null $expect_end_at 预计冲至多久
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $task_state 10-初始化，20-充电任务完成，30-异常中断
 * @property int|null $device_no 充电桩号
 * @property int|null $port_no 端口号
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks whereActualTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks whereBeginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks whereDeviceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks whereExpectEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks whereExpectTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks wherePortNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks whereTaskState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks whereUserId($value)
 * @mixin \Eloquent
 * @property float $user_cost 用户花费，元
 * @property string|null $form_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks whereFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks whereUserCost($value)
 * @property int $close_box
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeTasks whereCloseBox($value)
 */
class ChargeTasks extends Model
{

    const TASK_STATE_INIT = 0;//初始化
    const TASK_STATE_CHARGING = 10;//正在充
    const TASK_STATE_COMPLETE = 20;//充电完成,充满
    const TASK_STATE_END_ABMORMAL = 30;//充电异常中断
    const TASK_STATE_TIME_END = 40;//充电时间到，自动结束充电
    const TASK_STATE_USER_END = 50;//用户手动中断充电

    const CLOSE_BOX_NOT_SENT = 0;//未发送关闭箱子命令
    const CLOSE_BOX_HAS_SENT = 1;//已发送关闭箱子命令

    protected $table = 'charge_tasks';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public static function getStateMap($key = null)
    {
        $map = [
            self::TASK_STATE_INIT => '初始化，等待通电',
            self::TASK_STATE_CHARGING => '正在充电中',
            self::TASK_STATE_COMPLETE => '充满已完成',
            self::TASK_STATE_END_ABMORMAL => '充电异常中断',
            self::TASK_STATE_TIME_END => '充电时间到，自动结束',
            self::TASK_STATE_USER_END => '用户手动中断充电',
        ];
        return $key !== null ? $map[$key] : $map;
    }

    public static function getModeMap($key = null)
    {
        $map = [
            0 => '充满模式',
            3600 => '冲1小时',
            3600 * 2 => '冲2小时',
            3600 * 4 => '冲4小时',
        ];

        if ($key === null) {
            return $map;
        } elseif (isset($map[$key])) {
            return $map[$key];
        } else {
            return '冲' . number_format($key / 3600, 2) . '小时';
        }
    }

    /**
     * 获取充电结束的state，方便一点
     * @return array
     */
    public static function getFinishStateMap()
    {
        return [
            self::TASK_STATE_COMPLETE,
            self::TASK_STATE_END_ABMORMAL,
            self::TASK_STATE_TIME_END,
            self::TASK_STATE_USER_END,
        ];
    }

    /**
     * 新建任务[
     * @param $userId
     * @param $deviceNo
     * @param $portNo
     * @param $duration
     * @return bool
     */
    public function createTask($userId, $deviceNo, $portNo, $duration, $formId)
    {
        $task = new self();
        $task->user_id = $userId;
        $task->expect_time = $duration;
        $task->device_no = $deviceNo;
        $task->port_no = $portNo;
        $task->task_state = self::TASK_STATE_INIT;
        $task->form_id = $formId;
        return $task->save() ? $task->id : false;
    }

    /**
     * @param $deviceNo
     * @param $portNo
     * @return bool|int|mixed
     */
    public static function getLastTaskIdByDevice($deviceNo, $portNo)
    {
        $model = self::whereDeviceNo($deviceNo)->wherePortNo($portNo)->orderByDesc('id')->first();
        return $model ? $model->id : false;
    }

    public static function getLastTaskByDevice($deviceNo, $portNo)
    {
        $model = self::whereDeviceNo($deviceNo)->wherePortNo($portNo)->orderByDesc('id')->first();
        return $model;
    }


    /**
     * @param $taskId
     * @param $cost
     * @return int
     */
    public static function userCostAdd($taskId, $cost)
    {
        return self::find($taskId)->increment('user_cost', $cost);
    }

    /**
     * @param $userId
     * @return Model|null|static
     */
    public static function getLastTaskByUserId($userId)
    {
        $model = self::whereUserId($userId)->orderByDesc('id')->first();
        return $model;
    }
}
