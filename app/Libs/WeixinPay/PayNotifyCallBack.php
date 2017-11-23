<?php
/**
 * Created by PhpStorm.
 * User: chabby
 * Date: 17/11/21
 * Time: 下午6:09
 */
namespace App\Libs\WeixinPay;
use App\Services\OrderService;
use Illuminate\Support\Facades\Log;

require_once app_path() . "/Sdks/WxpayAPI_php_v3.0.1/lib/WxPay.Api.php";
require_once app_path() . "/Sdks/WxpayAPI_php_v3.0.1/lib/WxPay.Notify.php";

class PayNotifyCallBack extends \WxPayNotify
{
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new \WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = \WxPayApi::orderQuery($input);
        //Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        //Log::DEBUG("call back:" . json_encode($data));
        Log::info('callback:' . json_encode($data));

        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }

        $orderNo = $data['out_trade_no'];
        $thirdNo = $data['transaction_id'];
        OrderService::payment($orderNo, $thirdNo);

        Log::debug('callback msg:' . $msg);
        return true;
    }
}