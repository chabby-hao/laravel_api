<?php

namespace App\Services;


class ActivityService extends BaseService
{

    const SWITCH_OF_PAY_SEND_MONEY = true;//充值赠送开关

    public static function isOpenPaySendActivity()
    {
        return self::SWITCH_OF_PAY_SEND_MONEY;
    }

}