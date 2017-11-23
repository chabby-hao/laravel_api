<?php

namespace App\Http\Controllers\Api;

use App\Libs\Helper;
use App\Models\VerifyCode;
use App\Services\UserService;
use App\Services\VerifyCodeServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ChargeController extends Controller
{

    /**
     * 打开盒子
     * @param Request $request
     */
    public function openBox(Request $request)
    {

    }

    public function chargeBegin(Request $request)
    {
        $post = $request->post();
        $token = $post['token'];
        $mode = $post['mode']; //0=充满模式
        if(!$userInfo = UserService::getUserInfoByToken($token)){
            return Helper::responeseError('请重新登录');
        }

        return Helper::response($post);
    }


}
