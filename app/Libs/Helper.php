<?php

namespace App\Libs;

use App\Libs\ucpass\Ucpass;


class Helper{

    public static function response(array $data = [], $status = 200, array $headers = [])
    {
        $code = 200;
        $content = [
            'code'=>$code,
            'data'=> $data,
        ];
        return response($content, $status, $headers);
    }

    public static function responeseError($msg = '',array $data = [], $status = 200, array $headers = [])
    {

        $code = 500;

        $content = [
            'code'=>$code,
            'msg'=>$msg,
        ];
        $content = array_merge($content,$data);
        return response($content, $status, $headers);

    }

    //生成随机验证码
    public static function rand_verify_code($num){

        $count = 0;
        $str = '';
        while ($count < $num){
            $str .= rand(0,9);
            $count++;
        }

        return $str;

    }

    public static function send_message($options,$appId,$to,$templateId,$msg){
        $ucpaas = New Ucpass($options);
        //发送模板短信
        return $ucpaas->templateSMS($appId,$to,$templateId,$msg);

    }


}