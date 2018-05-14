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

class OpsService extends BaseService
{

    public static function startOps($cabinetId)
    {
        $cabinetNo = CabinetService::getCabinetNoById($cabinetId);
        if(CommandService::sendStartReplaceCmd($cabinetNo)){
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

}