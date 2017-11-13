<?php
/*
 * 小程序微信支付
 */
namespace App\Libs\WeixinPay;
use App\Libs\Helper;

class WeixinPay{
    protected $appid;
    protected $mch_id;
    protected $key;
    protected $openid;
    function __construct($appid,$openid,$mch_id,$key){
        $this->appid=$appid;
        $this->openid=$openid;
        $this->mch_id=$mch_id;
        $this->key=$key;
    }
    public function pay(){
        //统一下单接口
        $return=$this->weixinapp();
        return $return;
    }

    //统一下单接口
    private function unifiedorder(){
        $url='https://api.mch.weixin.qq.com/pay/unifiedorder';
        $parameters=array(
            'appid'=>$this->appid,//小程序ID
            'mch_id'=>$this->mch_id,//商户号
            'nonce_str'=>$this->createNoncestr(),//随机字符串
            'body'=>'测试',//商品描述
            'out_trade_no'=>'2015450806125346',//商户订单号
            'total_fee'=>floatval(0.01*100),//总金额 单位 分
            'spbill_create_ip'=>$_SERVER['REMOTE_ADDR'],//终端IP
            'notify_url'=>'http://www.weixin.qq.com/wxpay/pay.php',//通知地址
            'openid'=>$this->openid,//用户id
            'trade_type'=>'JSAPI'//交易类型
        );

        //统一下单签名
        $parameters['sign']=$this->getSign($parameters);
        $xmlData=Helper::arrayToXml($parameters);

        $return=Helper::xmlToArray(Helper::postXmlSSLCurl($xmlData,$url,60));

        return $return;
    }
    //微信小程序接口
    private function weixinapp(){
        //统一下单接口
        $unifiedorder=$this->unifiedorder();

        $parameters=array(
            'appId'=>$this->appid,//小程序ID
            'timeStamp'=>''.time().'',//时间戳
            'nonceStr'=>$this->createNoncestr(),//随机串
            'package'=>'prepay_id='.$unifiedorder['prepay_id'],//数据包
            'signType'=>'MD5'//签名方式
        );
        //签名
        $parameters['paySign']=$this->getSign($parameters);

        return $parameters;
    }
    //作用：产生随机字符串，不长于32位
    private function createNoncestr($length = 32 ){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ ) {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }
    //作用：生成签名
    private function getSign($Obj){
        foreach ($Obj as $k => $v){
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //签名步骤二：在string后加入KEY
        $String = $String."&key=".$this->key;
        //签名步骤三：MD5加密
        $String = md5($String);
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        return $result_;
    }

    ///作用：格式化参数，签名过程需要使用
    private function formatBizQueryParaMap($paraMap, $urlencode){
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v){
            if($urlencode)
            {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0){
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }
}