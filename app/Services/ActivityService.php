<?php

namespace App\Services;


use App\Libs\Helper;
use App\Libs\WxApi;
use App\Models\DeviceInfo;
use App\Models\WelfareCards;
use App\Models\WelfareDevices;
use App\Models\WelfareUsers;
use App\Models\WelfareWhiteLists;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivityService extends BaseService
{

    const SWITCH_OF_PAY_SEND_MONEY = true;//充值赠送开关

    public static function isOpenPaySendActivity()
    {
        return self::SWITCH_OF_PAY_SEND_MONEY;
    }

    public static function addCards($deviceNos, $cardName,$company, $phones = null)
    {
        DB::beginTransaction();
        try {

            $card = new WelfareCards();
            $card->limit_user = 0;
            $card->company = $company;
            $card->card_name = $cardName;
            if (!$card->save()) {
                throw new \Exception('cards add error');
            }

            foreach ($deviceNos as $deviceNo) {
                $welfareDevice = new WelfareDevices();
                $welfareDevice->card_id = $card->id;
                $welfareDevice->device_no = $deviceNo;
                if (!$welfareDevice->save()) {
                    throw new \Exception('welfare device add error');
                }
            }

            if ($phones) {
                foreach ($phones as $phone) {
                    $welfareWhite = new WelfareWhiteLists();
                    $welfareWhite->card_id = $card->id;
                    $welfareWhite->phone = $phone;
                    $welfareWhite->save();
                    if (!$welfareWhite->save()) {
                        throw new \Exception('welfare white list add error');
                    }
                }
                $card->limit_user = 1;
            }

            //添加二维码 $card
            $wxApi = new WxApi();
            $qrInfo = $wxApi->getQrImgForCards($card->id);
            $card->url_img = $qrInfo['img_url'];
            $url = Helper::getQrUrl($qrInfo['img_path']);
            $card->url = $url;
            if (!$card->save()) {
                throw new \Exception('cards save error');
            }


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('add Cards db error: ' . $e->getMessage());
            return false;
        }

        DB::commit();
        return true;


    }

    /**
     * 用户领取卡片
     * @param $userId
     * @param $cardId
     * @return bool
     */
    public static function userGetCard($userId, $cardId)
    {
        $welfareUser = new WelfareUsers();
        $welfareUser->card_id = $cardId;
        $welfareUser->user_id = $userId;
        return $welfareUser->save();
    }

}