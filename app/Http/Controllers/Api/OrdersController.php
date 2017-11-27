<?php

namespace App\Http\Controllers\Api;

use App\Libs\ErrorCode;
use App\Libs\Helper;
use App\Models\Orders;
use App\Models\VerifyCode;
use App\Services\UserService;
use App\Services\VerifyCodeServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class OrdersController extends Controller
{

    public function lists(Request $request)
    {
        if (!$userId = UserService::getUid()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        $order = new Orders();
        $lists = $order->getOrdersByUid($userId);
        return Helper::response($lists);
    }

}
