<?php

namespace App\Http\Controllers\Api;

use App\Libs\ErrorCode;
use App\Libs\Helper;
use App\Models\VerifyCode;
use App\Services\UserService;
use App\Services\VerifyCodeServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

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
            return Helper::responeseError(ErrorCode::$codeInvalid);
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
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        $sessonKey = $userInfo['session_key'];

        //中间会输出奇怪的字符，用ob消除
        ob_start();
        $pc = new \WXBizDataCrypt(\WxPayConfig::APPID, $sessonKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $output );
        ob_clean();

        if ($errCode == 0) {
            //不带区号的手机号
            $phone = json_decode($output, true)['purePhoneNumber'];
            UserService::bindPhone($token, $phone);
        } else {
            return Helper::responeseError(ErrorCode::$sessionKeyExpire);
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
        return Helper::responeseError(ErrorCode::$tokenExpire);
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
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        if($verifyCodeRow = VerifyCode::getByPhoneAndCode($phone, $verifyCode)){
            UserService::bindPhone($token, $phone);
            return Helper::response(['phone'=>$phone]);
        }else{
            return Helper::responeseError();
        }

    }


    public function sendMsgCode(Request $request)
    {
        $data = $request->post();
        $phone = $data['phone'];

        //验证手机号码格式
        $pattern = "/^1[34578]\d{9}/";

        if (!preg_match($pattern, $phone)) {
            $res = array('code' => 1, 'msg' => '手机号码格式不正确,非11位正确手机号');
            echo json_encode($res);
            die;
        }

        $accountid = config('app')['UCPAAS']['SMS_UCPAAS_ACCOUNT'];
        $token = config('app')['UCPAAS']['SMS_UCPAAS_TOKEN'];
        $options = array(
            'accountsid' => $accountid,
            'token' => $token,
        );
        $appId = config('app')['UCPAAS']['SMS_UCPAAS_APPID'];
        $to = $data['phone'];
        $templateId = config('app')['UCPAAS']['SMS_TEMPLATE_REGISTER'];
        if (env('APP_DEBUG')) {
            $templateId = '83783';
        }

        //生成随机6位验证码
        $code = Helper::rand_verify_code(4);

        //验证码存数据库，有效期30分钟
        $data = array(
            'phone' => $phone,
            'code' => $code,
            'expire_at' => time() + 30 * 60,
        );

        //判断手机号是否有未过期的验证码
        $verifycodeService = new VerifyCodeServices();
        $verifycodeModel = new VerifyCode();

        $ver_code = $verifycodeService->codeIsExpired($phone);
        if (!$ver_code) {//新增数据库验证码
            $id = VerifyCode::create($data);
        } else {//更新数据库验证码
            $id = DB::table('verify_code')
                ->where('phone', $phone)
                ->where('expire_at', '>', time() - 30 * 60)
                ->update(['code' => $code, 'expire_at' => time() + 30 * 60]);

        }

        //验证码保存成功，则发送验证码给用户
        if ($id) {
            $msg = '您的验证码是' . $code;
            $ucpaas = Helper::send_message($options, $appId, $to, $templateId, $msg);
            $ucpaas_res = json_decode($ucpaas, true);
            var_dump($ucpaas_res);
        }
    }

    public function checkToken(Request $request)
    {
        $token = $request->post('token');
        if($token && UserService::getUserInfoByToken($token)){
            return Helper::response([]);
        }else{
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }
    }

    public function balance(Request $request)
    {
        $token = $request->get('token');
        if(!$userInfo = UserService::getUserInfoByToken($token)){
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }
        $userId = $userInfo['uid'];
        $user = UserService::getUserByUserId($userId);
        $balance = $user ? $user['user_balance'] : 0;
        $balance = number_format($balance, 2);
        return Helper::response(['balance'=>$balance]);
    }

}
