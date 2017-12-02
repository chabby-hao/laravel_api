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
use App\Libs\WeixinPay\PayNotifyCallBack;
use App\Libs\WeixinPay\WeixinPay;
use App\Services\OrderService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeixinPayController extends Controller
{


    //支付费用
    public function payJoinfee(Request $request)
    {
        $token = $request->post('token');
        $orderAmount = $request->post('order_amount', 0.01);
        if (!$token) {
            return Helper::responeseError('请登录', ['token' => $token]);
        }
        $userId = UserService::getUserInfoByToken($token)['user_id'];
        $orderNo = OrderService::createOrder($userId, $orderAmount);
        //创建支付参数
        $payArgs = $this->_createPayArgs($orderNo, $orderAmount);
        return Helper::response($payArgs);
    }

    /**
     * 创建小程序支付参数
     * @param $orderNo
     * @param $orderAmount
     * @return array
     */
    private function _createPayArgs($orderNo, $orderAmount)
    {
        //统一下单
        $openid = UserService::$userInfo['openid'];
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("充值");
        $input->SetAttach('attach');
        $input->SetOut_trade_no($orderNo);
        $input->SetTotal_fee($orderAmount * 100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("余额充值");
        $input->SetNotify_url(route('wxnotify'));
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openid);
        $order = \WxPayApi::unifiedOrder($input);
        //获取小程序支付参数
        $parameters = \WxPayApi::getMiniPayArgs($order);
        return $parameters;
    }

    public function wxNotify()
    {
        Log::info('wxnotify :' . file_get_contents("php://input"));
        $wxNotify = new PayNotifyCallBack();
        $wxNotify->Handle(true);
        if($wxNotify->GetReturn_code() === 'SUCCESS'){
            //支付成功
        }else{
            Log::error('wxnotify handel error: ' . $wxNotify->GetReturn_msg());
        }

    }


}
