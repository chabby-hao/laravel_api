<?php

namespace App\Http\Controllers\Api;

use App\Libs\ErrorCode;
use App\Libs\Helper;
use App\Services\BoxService;
use App\Services\ChargeService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

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
        $deviceId = $post['device_id'];//设备id

        Log::debug('chargeBegin :'. json_encode($post));

        if(!$userId = UserService::getUserId()){
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        //充电
        ChargeService::startCharge($userId, $deviceId, $mode);

        return $this->responseOk();
    }

    /**
     * 结束充电(用户主动调用)
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function chargeEnd(Request $request)
    {
        $deviceId = $request->input('device_id');

        ChargeService::endChargeByUser($deviceId);

        return $this->responseOk();
    }

    /**
     * 充电停止推送（python调用）
     * @param Request $request
     */
    public function chargeHalt(Request $request)
    {
        $deviceNo = $request->input('device_no');
        $portNo = $request->input('port_no');
        $type = $request->input('type');

        return Helper::response([
            'device_no'=>$deviceNo,
            'port_no'=>$portNo,
            'type'=>$type,
        ]);
    }

    /**
     * 充电时间
     * @param Request $request
     */
    public function chargingTime(Request $request)
    {
        $token = $request->input('token');
        if(!$userId = UserService::getUserId()){
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }
        if(!$mins = ChargeService::getLastChargingTimeByUserId($userId)){
            return Helper::responeseError(ErrorCode::$chargeTaskNotFind);
        }

        return Helper::response(['mins'=>$mins]);
    }


}
