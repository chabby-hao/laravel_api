<?php

namespace App\Http\Controllers\Api;

use App\Libs\ErrorCode;
use App\Libs\Helper;
use App\Models\User;
use App\Models\UserRefunds;
use App\Models\VerifyCode;
use App\Services\ActivityService;
use App\Services\CabinetService;
use App\Services\OpsService;
use App\Services\UserService;
use App\Services\VerifyCodeServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class OpsController extends Controller
{

    /**
     * 开始运维
     */
    public function startOps(Request $request)
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        //检查用户是否有运维权限
        if(!UserService::checkOpsPermis($userId)){
            return Helper::responeseError(ErrorCode::$notOpsUser);
        }
        //{"cabinetId":'1'}

        $input = $this->checkRequireParams(['qr'], $request->input());
        $qr = $input['qr'];

        $cabinetId = CabinetService::getCabinetIdByQr($qr);

        if($cabinetId && OpsService::startOps($cabinetId)){
            return $this->responseOk();
        }

        return Helper::responeseError(ErrorCode::$operationFail);
    }
    
    public function endOps(Request $request)
    {
        $userId = $this->checkUser();
        //检查用户是否有运维权限
        if(!UserService::checkOpsPermis($userId)){
            return Helper::responeseError(ErrorCode::$notOpsUser);
        }

        $input = $this->checkRequireParams(['qr'], $request->input());
        $qr = $input['qr'];

        $cabinetId = CabinetService::getCabinetIdByQr($qr);

        if($cabinetId && OpsService::endOps($cabinetId)){
            return $this->responseOk();
        }

        return Helper::responeseError(ErrorCode::$operationFail);

    }


}
