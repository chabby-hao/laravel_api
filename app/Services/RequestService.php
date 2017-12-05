<?php
namespace App\Services;

use App\Libs\Helper;

class RequestService extends  BaseService
{

    private static $requestSecret = 'M3vsFKCKTTUK2QnjkVEyBkACKUmiTcRZ';

    public static function checkSign($data)
    {
        $key = self::$requestSecret;
        return Helper::commonCheckSign($data,$key);
    }

}