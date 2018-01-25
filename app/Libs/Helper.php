<?php

namespace App\Libs;

use App\Libs\ucpass\Ucpass;
use Illuminate\Support\Facades\Log;


class Helper
{

    /**
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public static function response(array $data = [], $status = 200, array $headers = [])
    {
        $code = 200;
        $content = [
            'code' => $code,
            'msg' => 'success',
            'data' => $data,
        ];
        //Log::debug('response------------- ' . json_encode($content));
        return response($content, $status, $headers);
    }

    /**
     * @param int $code
     * @param array $data
     * @param array $replaces
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public static function responeseError($code = 500, array $data = [], $replaces = [], $status = 200, array $headers = [])
    {

        $errMsg = ErrorCode::getErrMsg();
        $content = [
            'code' => $code,
            'msg' => isset($errMsg[$code]) ? $errMsg[$code] : '',
        ];
        if ($replaces) {
            $newReplaces = [];
            foreach ($replaces as $k=>$replace){
                $newReplaces['{' . $k . '}'] = $replace;
            }
            $content['msg'] = strtr($content['msg'], $newReplaces);
        }
        $content = array_merge($content, $data);
        //Log::error('response error----------- ' . json_encode($content));
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

    public static function sendShortMessage($options, $appId, $to, $templateId, $msg)
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

    /*
     * 验证签名(按Asci从小到大排序)
     * @param $data
     * @param $key
     * @return bool
     */
    public static function commonCheckSign($data, $key)
    {
        if (!isset($data['sign'])) {
            return false;
        }

        $sign = $data['sign'];
        unset($data['sign']);
        ksort($data);

        $str = '';
        if ($data) {
            foreach ($data as $row) {
                $str .= $row;
            }
        }
        $str .= $key;

        return md5($str) === $sign;
    }

    /**
     * 过滤后，返回必要的字段
     * @param $arrFilter
     * @param $arrData
     * @param bool $returnKey
     * @param array $allowEmptys
     * @return array|bool|string
     */
    public static function arrayRequiredCheck($arrFilter, $arrData, $returnKey = false, $allowEmptys = [])
    {
        $data = [];
        foreach ($arrFilter as $filter) {
            if (array_key_exists($filter, $arrData) && $arrData[$filter] !== '' && $arrData[$filter] !== null) {
                $data[$filter] = $arrData[$filter];
            }elseif(in_array($filter, $allowEmptys)){
                $data[$filter] = '';
            } else {
                if ($returnKey) {
                    return $filter;
                } else {
                    return false;
                }
            }
        }
        return $data;
    }

    public static function getQrUrl($imgPath)
    {
        $qrcode = new \QrReader($imgPath);
        $url = $qrcode->text();
        //有时会解析失败，解析失败调用api来解析
        if (!$url) {
            Log::error('qr decode fail with data ' . $imgPath);
            $qrApi = new QrApi();
            $url = $qrApi->qrdecode($imgPath);
            if (!$url) {
                Log::error('qr decode fail for api with data ' . $imgPath);
                return false;
            }
        }
        return $url;

    }

    /**
     * 转成一维数组
     * @param array $array
     * @param $keyname
     */
    public static function transToOneDimensionalArray(array $array, $keyname)
    {
        if($array){
            foreach ($array as &$item){
                $item = $item[$keyname];
            }
        }
        return $array;
    }

}