<?php

namespace App\Libs;


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
            'code'=>isset($data['code']) ? intval($data['code']) : $code,
            'msg'=>$msg,
            'data'=>$data,
        ];
        return response($content, $status, $headers);

    }


}