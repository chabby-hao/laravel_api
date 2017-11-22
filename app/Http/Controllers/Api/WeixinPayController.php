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

class WeixinPayController extends Controller{


    //支付费用
    public function payJoinfee(Request $request){
        $token = $request->post('token');
        if(!$token){
            return Helper::responeseError('请登录',['token'=>$token]);
        }
        $openid = UserService::getPhoneByToken($token);
        $appid=config('app.config.wx_appid');
        $mch_id='1264801801';
        $key='';

        $weixinpay = new WeixinPay($appid,$openid,$mch_id,$key);
        $return=$weixinpay->pay();

        var_dump($return);
    }

    public function wxNotify()
    {

    }


}
