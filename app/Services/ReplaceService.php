<?php

namespace App\Services;

use App\Libs\WxApi;
use App\Models\Appointments;
use App\Models\Cabinets;
use App\Models\ChargeTasks;
use App\Models\DeviceInfo;
use App\Models\ReplaceTasks;
use App\Models\User;
use App\Models\WelfareUsers;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Log;
use Psy\Command\WhereamiCommand;

class ReplaceService extends BaseService
{

    const COST_AMOUNT = 2;

    /**
     * 开始更换电池任务
     * @param $cabinetId
     */
    public static function startReplaceBattery($userId, $cabinetId)
    {

        $cabinet = Cabinets::find($cabinetId);
        $cabinetNo = $cabinet->cabinet_no;

        //db 入库
        if(!$model = ReplaceTasks::newTask($userId, $cabinetId)){
            return false;
        }

        $appo = Appointments::whereUserId($userId)->whereCabinetId($cabinetId)->where('expired_at', '>', Carbon::now()->toDateTimeString())->first();
        if($appo){
            $appo->state = 1;
            $appo->save();
        }

        //下发换电指令
        return CabinetService::sendReplaceCommand($cabinetNo, $model->id, $model->battery_id1);
    }

    /**
     *  用户结费
     */
    public static function userCost($taskId)
    {
        $task = ReplaceTasks::find($taskId);
        if($task){
            $cost = self::COST_AMOUNT;
            $task->user_cost = $cost;
            $task->actual_cost = $cost;
            $task->save();
            $user = User::find($task->user_id);
            if ($user->user_balance > 0) {
                //$charge->cost_type = ChargeTasks::COST_TYPE_BALANCE;
                $field = 'user_balance';
            } else {
                //$charge->cost_type = ChargeTasks::COST_TYPE_PRESNET;
                $field = 'present_balance';
            }
            //扣款
            User::chargeCost($task->user_id, $cost, $field);
        }
    }

    public static function appointment($userId, $cabinetId, $batteryLevel)
    {
        //预约加锁

        $appointment = new Appointments();
        $appointment->user_id = $userId;
        $appointment->cabinet_id = $cabinetId;
        $appointment->battery_level = $batteryLevel;
        $appointment->expired_at = Carbon::now()->addMinutes(30)->toDateTimeString();
        $appointment->save();
    }

    /**
     * 是否已经存在进行中的换电任务
     * @param $cabinetId
     * @return bool
     */
    public static function checkProcessingTask($cabinetId)
    {
        $task = ReplaceTasks::whereCabinetId($cabinetId)->whereIn('state',[ReplaceTasks::TASK_STATE_INIT, ReplaceTasks::TASK_STATE_PROCESSING])->first();
        return $task ? true : false;
    }

    public static function checkProcessingTaskByUserId($userId)
    {
        $task = ReplaceTasks::whereUserId($userId)->whereIn('state',[ReplaceTasks::TASK_STATE_INIT, ReplaceTasks::TASK_STATE_PROCESSING])->first();
        return $task ? true : false;
    }

    public static function isAppointment($cabinetId, $userId)
    {
        $model = Appointments::whereUserId($userId)->whereCabinetId($cabinetId)->where('expired_at', '>', Carbon::now()->toDateTimeString())->first();
        return $model ? true : false;
    }

}