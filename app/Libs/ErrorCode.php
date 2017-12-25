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

    //public static $invalidDeviceId = 2004;//设备id不存在

    public static $chargeTaskNotFind = 2005;//充电任务不存在

    public static $qrCodeNotFind = 2006;//找不到充电二维码

    public static $deviceNotOnline = 2007;//设备离线

    public static $deviceNotUseful = 2008;//设备不可用

    public static $balanceNotEnough = 2009;//用户余额不足

    public static $chargeNotFinishYet = 2010;//充电还未结束

    public static $refundFail = 2011;//提交退款失败，请确认还有余额

    public static function getErrMsg()
    {
        return [
            self::$qrCodeNotFind => '二维码有误',
            self::$deviceNotOnline => '设备离线',
            self::$deviceNotUseful => '充电口暂不可用，请稍后再试',
            self::$balanceNotEnough =>'余额不足，请先充值',
        ];
    }


}