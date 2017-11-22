<?php

namespace App\Libs;

use App\Libs\ucpass\Ucpass;


class Helper
{

    public static function response(array $data = [], $status = 200, array $headers = [])
    {
        $code = 200;
        $content = [
            'code' => $code,
            'data' => $data,
        ];
        return response($content, $status, $headers);
    }

    public static function responeseError($msg = '', array $data = [], $status = 200, array $headers = [])
    {

        $code = 500;

        $content = [
            'code' => $code,
            'msg' => $msg,
        ];
        $content = array_merge($content, $data);
        return response($content, $status, $headers);

    }

    //生成随机验证码
    public static function rand_verify_code($num)
    {

        $count = 0;
        $str = '';
        while ($count < $num) {
            $str .= rand(0, 9);
            $count++;
        }

        return $str;

    }

    public static function send_message($options, $appId, $to, $templateId, $msg)
    {
        $ucpaas = New Ucpass($options);
        //发送模板短信
        return $ucpaas->templateSMS($appId, $to, $templateId, $msg);

    }

    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . arrayToXml($val) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    public static function postXmlCurl($url, $xmlData)
    {
        $header[] = "Content-type: text/xml";      //定义content-type为xml,注意是数组
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            printcurl_error($ch);
        }
        curl_close($ch);
        return $response;
    }

    public static function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }


}