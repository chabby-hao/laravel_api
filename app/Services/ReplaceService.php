<?php

namespace App\Services;

use App\Libs\WxApi;
use App\Models\Appointments;
use App\Models\ChargeTasks;
use App\Models\DeviceInfo;
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
    public static function startReplaceBattery($cabinetId)
    {

        //db 入库

        //下发换电指令



    }

    public static function appointment($userId, $cabinetId)
    {
        $appointment = new Appointments();
        $appointment->user_id = $userId;
        $appointment->cabinet_id = $cabinetId;
        $appointment->expired_at = Carbon::now()->addMinutes(30)->getTimestamp();
        $appointment->save();
    }


}