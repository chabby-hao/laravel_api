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

    protected function checkPermis()
    {
        $userId = $this->checkUser();
        //检查用户是否有运维权限
        if(!UserService::checkOpsPermis($userId)){
            return Helper::responeseError(ErrorCode::$notOpsUser);
        }
        return $userId;
    }


    /**
     * 开始运维
     */
    public function startOps(Request $request)
    {
        $this->checkPermis();

        $input = $this->checkRequireParams(['qr'], $request->input());
        $qr = $input['qr'];

        $cabinetId = CabinetService::getCabinetIdByQr($qr);

        if(!CabinetService::isCabinetUseful($cabinetId)){
            return Helper::responeseError(ErrorCode::$cabinetUnuseful);
        }

        if(CabinetService::isReplacing($cabinetId)){
            return Helper::responeseError(ErrorCode::$isReplacing);
        }

        if($cabinetId && OpsService::startOps($cabinetId)){
            return $this->responseOk();
        }

        return Helper::responeseError(ErrorCode::$operationFail);
    }
    
    public function endOps(Request $request)
    {
        $this->checkPermis();

        $input = $this->checkRequireParams(['qr'], $request->input());
        $qr = $input['qr'];

        $cabinetId = CabinetService::getCabinetIdByQr($qr);

        if(!CabinetService::isCabinetUseful($cabinetId)){
            return Helper::responeseError(ErrorCode::$cabinetUnuseful);
        }

        if($cabinetId && OpsService::endOps($cabinetId)){
            return $this->responseOk();
        }

        return Helper::responeseError(ErrorCode::$operationFail);

    }

    public function opsInfo()
    {
        $this->checkPermis();

        $input = $this->checkRequireParams(['qr']);
        $qr = $input['qr'];

        $cabinetId = CabinetService::getCabinetIdByQr($qr);

        list($waitOps, $hasOps) = OpsService::getOpsInfo($cabinetId);

        return Helper::response([
            'waitOps'=>$waitOps,
            'hasOps'=>$hasOps,
        ]);

    }
}
