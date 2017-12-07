<?php

namespace App\Http\Controllers;

use App\Libs\ErrorCode;
use App\Libs\Helper;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
    public function checkRequireParams($arrRequire, $data)
    {
        $res = Helper::arrayRequiredCheck($arrRequire, $data, true);
        if(is_string($res)){
            return Helper::responeseError(ErrorCode::$errParams, ['msg'=>"$res is required"]);
        }
        return $res;
    }

}
