<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Libs\Helper;
use App\Services\ActivityService;
use Illuminate\Http\Request;

class ActivityController extends Controller
{

    public function isOpenPaySend(Request $request)
    {
        $output = [
            'open'=>0,
        ];
        if(ActivityService::SWITCH_OF_PAY_SEND_MONEY){
            $output['open'] = 1;
        }
        return Helper::response($output);
    }

}
