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

        //下发换电指令
        return CabinetService::sendReplaceCommand($cabinetNo, $model->id, $model->battery_id1);
    }

    public static function appointment($userId, $cabinetId)
    {
        //预约加锁

        $appointment = new Appointments();
        $appointment->user_id = $userId;
        $appointment->cabinet_id = $cabinetId;
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
        $task = ReplaceTasks::whereCabinetId($cabinetId)->whereBetween('state',[ReplaceTasks::TASK_STATE_INIT, ReplaceTasks::TASK_STATE_PROCESSING])->first();
        return $task ? true : false;
    }

}