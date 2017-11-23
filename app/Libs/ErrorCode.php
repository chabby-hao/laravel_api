<?php

namespace App\Libs;



class ErrorCode
{

    public static $tokenExpire = 2001;//token过期

    public static $codeInvalid = 2002;//微信code失效

    public static $sessionKeyExpire = 2003;//微信sessionkey过期

}