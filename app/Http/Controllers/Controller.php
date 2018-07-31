<?php

namespace App\Http\Controllers;

use App\Libs\ErrorCode;
use App\Libs\Helper;
use App\Services\UserService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(Request $request)
    {
        //Log::debug('reques---------- ' . json_encode($request->input()));
    }

    /**
     * 返回成功
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function responseOk()
    {
        return Helper::response();
    }

    /**
     * @param $data
     * @return array|bool|\Illuminate\Contracts\Routing\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response
     */
    public function checkRequireParams($arrRequire, $data = null)
    {
        if(!$data){
            $data = \Request::input();
        }
        $res = Helper::arrayRequiredCheck($arrRequire, $data, true);
        if(is_string($res)){
            return Helper::responeseError(ErrorCode::$errParams, ['msg'=>"$res is required"]);
        }
        return $res;
    }

    protected function checkUser()
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }
        return $userId;
    }

    protected function getDaterange($preDay = null)
    {
        $dateRange = Input::get('daterange');
        if ($dateRange) {
            if (strpos($dateRange, '~') !== false) {
                list($startDatetime, $endDatetime) = explode('~', $dateRange);
            } else {
                list($startDatetime, $endDatetime) = explode('-', $dateRange);
            }
        } else {
            $preDay = $preDay ?: Carbon::now()->startOfDay()->toDateTimeString();
            list($startDatetime, $endDatetime) = [$preDay, Carbon::now()->endOfDay()->toDateTimeString()];
        }
        return [trim($startDatetime), trim($endDatetime)];
    }

}
