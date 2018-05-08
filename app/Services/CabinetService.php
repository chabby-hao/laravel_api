<?php

namespace App\Services;

use App\Libs\WxApi;
use App\Models\ChargeTasks;
use App\Models\DeviceInfo;
use App\Models\User;
use App\Models\WelfareUsers;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Log;
use Psy\Command\WhereamiCommand;

class CabinetService extends BaseService
{

    /**
     * 柜子是否可用
     * @param $cabinetId
     */
    public static function isCabinetUseful($cabinetId)
    {
        //判断在线状态
    }

    /**
     * 下发换电指令
     * @param $cabinetId
     */
    public static function sendReplaceCommand($cabinetId)
    {

    }


}