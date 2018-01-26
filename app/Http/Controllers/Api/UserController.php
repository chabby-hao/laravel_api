<?php

namespace App\Http\Controllers\Api;

use App\Libs\ErrorCode;
use App\Libs\Helper;
use App\Models\User;
use App\Models\UserRefunds;
use App\Models\VerifyCode;
use App\Services\ActivityService;
use App\Services\UserService;
use App\Services\VerifyCodeServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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

        $phone = UserService::getPhoneByToken($token);
        return Helper::response(['token'=>$token,'phone'=>$phone]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function bindPhoneForWx(Request $request)
    {
        //$data = $request->post();
        $data = $this->checkRequireParams(['detail','token'], $request->input());
        if ($data instanceof Response) {
            return $data;
        }
        $detail = $data['detail'];
        $iv = $detail['iv'];
        $encryptedData = $detail['encryptedData'];
        $token = $data['token'];

        if(!$sessonKey = UserService::getSessionKeyByToken($token)){
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }


        //中间会输出奇怪的字符，用ob消除
        ob_start();
        $pc = new \WXBizDataCrypt(\WxPayConfig::APPID, $sessonKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $output );
        ob_clean();

        Log::debug("bindPhone $sessonKey,$encryptedData,$iv,$errCode,$output");
        if ($errCode == 0) {
            //不带区号的手机号
            $phone = json_decode($output, true)['purePhoneNumber'];
            UserService::bindPhone($token, $phone, User::LOGIN_TYPE_WEIXIN);
        } else {
            return Helper::responeseError(ErrorCode::$sessionKeyExpire);
        }

        return Helper::response(['phone'=>$phone]);
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

        /*if(!$userInfo = UserService::getUserInfoByToken($token)){
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }*/

        if($verifyCodeRow = VerifyCode::getByPhoneAndCode($phone, $verifyCode)){
            UserService::bindPhone($token, $phone, User::LOGIN_TYPE_PHONE);
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
            return Helper::responeseError(ErrorCode::$phoneInvalid);
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
            $ucpaas = Helper::sendShortMessage($options, $appId, $to, $templateId, $msg);

            Log::debug('verify code response:' . $ucpaas);
            $ucpaas = json_decode($ucpaas, true);
            if($ucpaas['resp']['respCode'] == '000000'){
                return $this->responseOk();
            }
        }
        return Helper::responeseError(ErrorCode::$phoneVerifyCodeSendFailed);

    }

    public function checkToken(Request $request)
    {
        $token = $request->post('token');
        if($token && $phone = UserService::getPhoneByToken($token)){
            return Helper::response(['phone'=>$phone]);
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
        $balance = number_format($userInfo['user_balance'], 2);
        $present = number_format($userInfo['present_balance'], 2);
        return Helper::response(['balance'=>$balance,'present'=>$present]);
    }

    /**
     * 退款
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function refund()
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        if ($refundId = UserService::userRefund($userId)){
            return $this->responseOk();
        }

        return Helper::responeseError(ErrorCode::$refundFail);

    }

    public function hasRefund()
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        $refund = UserRefunds::whereUserId($userId)->first();
        if($refund){
            return Helper::response(['has_refund'=>1]);
        }else{
            return Helper::response(['has_refund'=>0]);
        }
    }

    /**
     * 用户反馈
     */
    public function feedback(Request $request)
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }
        $content = $request->input('content');

        UserService::addFeedBack($userId, $content);
        return $this->responseOk();
    }

    public function cardsList(Request $request)
    {
        if(!$userId = UserService::getUserId()){
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        $data = ActivityService::getCardsByUserId($userId);
        return Helper::response($data);

    }

}
