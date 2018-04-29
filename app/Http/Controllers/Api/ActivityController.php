<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Libs\ErrorCode;
use App\Libs\Helper;
use App\Models\WelfareCards;
use App\Models\WelfareUsers;
use App\Models\WelfareWhiteLists;
use App\Services\ActivityService;
use App\Services\UserService;
use function Hprose\Future\error;
use Illuminate\Http\Request;

class ActivityController extends Controller
{

    public function isOpenPaySend(Request $request)
    {
        $output = [
            'open'=>0,
            'show_modal'=>1,//首页是否展示弹框
        ];
        if(ActivityService::isOpenPaySendActivity()){
            $output['open'] = 1;
        }
        return Helper::response($output);
    }

    /**
     * 领取福利卡
     */
    public function getCard(Request $request)
    {
        if(!$userId = UserService::getUserId()){
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        $cardId = $request->input('card');

        $card = WelfareCards::find($cardId);
        if(!$card){
            return Helper::responeseError(ErrorCode::$cardInvalid);
        }

        if(WelfareUsers::whereUserId($userId)->whereCardId($cardId)->first()){
            //已经领取过
            return Helper::responeseError(ErrorCode::$hasGotCard);
            /*return Helper::response([
                'toast'=>'您已经领取过福利卡，无需再次领取',
            ]);*/
        }

        if($card->limit_user){
            //限制用户,去匹配白名单
            $whiteList = WelfareWhiteLists::wherePhone(UserService::$userInfo['phone'])->whereCardId($cardId)->first();
            if($whiteList){
                //匹配到,可以领取
                ActivityService::userGetCard($userId, $cardId);
            }else{
                //非法用户
                return Helper::responeseError(ErrorCode::$notInWhiteList);
            }
        }else{
            //不限制用户,直接领取
            ActivityService::userGetCard($userId, $cardId);
        }

        return Helper::response([
            'toast'=>'您已成功领取公司为您提供的电瓶车智能充电服务，请至园区内的充电棚给您的爱车充电吧。此卡只适用于' . $card->company . '充电棚。',
        ]);

    }

}
