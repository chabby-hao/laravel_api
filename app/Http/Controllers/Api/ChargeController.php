<?php

namespace App\Http\Controllers\Api;

use App\Libs\ErrorCode;
use App\Libs\Helper;
use App\Models\DeviceInfo;
use App\Models\VerifyCode;
use App\Services\BoxService;
use App\Services\ChargeService;
use App\Services\UserService;
use App\Services\VerifyCodeServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ChargeController extends Controller
{

    /**
     * 打开盒子
     * @param Request $request
     */
    public function openBox(Request $request)
    {
        if(!BoxService::isOpen()){
            BoxService::openBox();
            return $this->responseOk();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function chargeBegin(Request $request)
    {
        $post = $request->post();
        $mode = $post['mode']; //0=充满模式,其余为多少小时
        $deviceId = $post['deviceId'];//设备id

        Log::debug('chargeBegin :'. json_encode($post));

        if(!$userId = UserService::getUid()){
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        //充电
        ChargeService::startCharge($userId, $deviceId, $mode);

        return $this->responseOk();
    }

    /**
     * 结束充电
     * @param Request $request
     */
    public function chargeEnd(Request $request)
    {

    }


}
