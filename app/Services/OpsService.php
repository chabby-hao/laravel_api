<?php

namespace App\Services;

use App\Libs\WxApi;
use App\Models\ChargeTasks;
use App\Models\DeviceInfo;
use App\Models\User;
use App\Models\WelfareUsers;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Psy\Command\WhereamiCommand;

class OpsService extends BaseService
{

    public static function startOps($cabinetId)
    {
        $cabinetNo = CabinetService::getCabinetNoById($cabinetId);
        if(CommandService::sendStartOps($cabinetNo)){
            Log::info("start ops id:$cabinetId no:$cabinetNo");
            return true;
        }
        return false;
    }

    public static function endOps($cabinetId)
    {
        $cabinetNo = CabinetService::getCabinetNoById($cabinetId);
        if(CommandService::sendEndOps($cabinetNo)){
            Log::info("end ops id:$cabinetId no:$cabinetNo");
            return true;
        }
        return false;
    }

    public static function getOpsInfo($cabinetId)
    {
        $cabinetNo = CabinetService::getCabinetNoById($cabinetId);
        $data = CabinetService::getCabinetInfo($cabinetNo);
        $waitOps = $data['wait_for_maintenance'] ? : 0;
        $hasOps = $data['maintenance'] ? : 0;
        return [$waitOps, $hasOps];
    }

}