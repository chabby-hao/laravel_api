<?php
/**
 * Created by PhpStorm.
 * User: eme
 * Date: 2017/11/13
 * Time: 上午10:55
 */
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Libs\Helper;
use App\Libs\WeixinPay\WeixinPay;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeixinPayController extends Controller{


    //支付费用
    public function payJoinfee(Request $request){
        $token = $request->post('token');
        if(!$token){
            return Helper::responeseError('请登录',['token'=>$token]);
        }
        $openid = UserService::getOpenIdByToken($token);

        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("充值");
        $input->SetAttach($token);
        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee(1);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("余额充值");
        $input->SetNotify_url(route('wxnotify'));
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openid);
        $order = \WxPayApi::unifiedOrder($input);

        //获取小程序支付参数
        $parameters = \WxPayApi::getMiniPayArgs($order);

        return Helper::response($parameters);
    }

    public function wxNotify()
    {
        Log::info('wxnotify :' . file_get_contents("php://input"));
    }


}
