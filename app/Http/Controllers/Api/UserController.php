<?php

namespace App\Http\Controllers\Api;

use App\Libs\Helper;
use App\Models\VerifyCode;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function loginByCode(Request $request)
    {
        $code = $request->post('code');
        $token = UserService::loginByCode($code);
        if(!$token){
            return Helper::responeseError('请重新登录');
        }
        return Helper::response(['token'=>$token]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function bindPhoneForWx(Request $request)
    {
        $data = $request->post();
        $detail = $data['detail'];
        $iv = $detail['iv'];
        $encryptedData = $detail['encryptedData'];
        $token = $data['token'];

        if(!$userInfo = UserService::getUserInfoByToken($token)){
            return Helper::responeseError('请重新登录');
        }else{
            $userInfo = json_decode($userInfo, true);
        }
        $sessonKey = $userInfo['session_key'];

        $pc = new \WXBizDataCrypt(UserService::WX_APPID, $sessonKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $output );

        if ($errCode == 0) {
            //不带区号的手机号
            $phone = json_decode($output, true)['purePhoneNumber'];
            UserService::bindPhone($token, $phone);
        } else {
            return Helper::responeseError('用户验证失败');
        }

        return Helper::response(['phone'=>$phone]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function checkLogin(Request $request)
    {
        $data = $request->post();
        $token = $data['token'];
        if($phone = UserService::getPhoneByToken($token)){
            return Helper::response([
                'phone'=>$phone,
            ]);
        }
        return Helper::responeseError('请重新登录');
    }

    /**
     * phone+verify_code
     * @param Request $request
     */
    public function login(Request $request)
    {
        $data = $request->post();
        $token = $data['token'];
        $phone = $data['phone'];
        $verifyCode = $data['verify_code'];

        if(!$userInfo = UserService::getUserInfoByToken($token)){
            return Helper::responeseError('token失效');
        }

        if($verifyCodeRow = VerifyCode::getByPhoneAndCode($phone, $verifyCode)){
            UserService::bindPhone($token, $phone);
            return Helper::response(['phone'=>$phone]);
        }else{
            return Helper::responeseError('验证码有误');
        }



    }

}
