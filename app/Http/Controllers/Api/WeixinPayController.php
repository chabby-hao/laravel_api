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

class WeixinPayController extends Controller{


    //支付费用
    public function payJoinfee(){

        $appid='wx888888888';
        $openid='wxa18b666bc3bec5d9';
        $mch_id='141388888';
        $key='9A0A86888888888';

//        import('Weixin.Lib.WeixinPay');
        $weixinpay = new WeixinPay($appid,$openid,$mch_id,$key);
        $return=$weixinpay->pay();

        var_dump($return);
    }


}
