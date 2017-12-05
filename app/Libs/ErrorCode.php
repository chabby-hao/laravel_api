<?php

namespace App\Libs;



class ErrorCode
{

    //逻辑层
    public static $errParams = 1001;//参数错误

    public static $errSign = 1002;//签名错误


    //业务层
    public static $tokenExpire = 2001;//token过期

    public static $codeInvalid = 2002;//微信code失效

    public static $sessionKeyExpire = 2003;//微信sessionkey过期

    public static $invalidDeviceId = 2004;//设备id不存在

    public static $chargeTaskNotFind = 2005;//充电任务不存在

    public static $qrCodeNotFind = 2006;//找不到充电二维码

}