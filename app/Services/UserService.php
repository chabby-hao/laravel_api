<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserToken;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class UserService extends BaseService
{


    /*  'openid' => $openid,
        'user_id' => $user['id'],
        'phone' => !empty($user['phone']) ? $user['phone'] : '',*/
    public static $userInfo = [];

    public static function getUserId()
    {
        if (!empty(static::$userInfo['user_id'])) {
            return static::$userInfo['user_id'];
        }
        return false;
    }


    public static function getOpenid($code)
    {
        $appid = \WxPayConfig::APPID;
        $secert = \WxPayConfig::APPSECRET;
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$secert&js_code=$code&grant_type=authorization_code";
        $json = file_get_contents($url);

        $arr = json_decode($json, true);
        if (isset($arr['openid'])) {
            return $arr;
        } else {
            return false;
        }
    }

    /**
     * @param $code
     * @return bool|string
     */
    public static function loginByCode($code)
    {
        if (!$data = self::getOpenid($code)) {
            return false;
        }
//        "session_key":"siDizJgW82HgNEvnPMZCKg==",
//                    "expires_in":7200,
//                    "openid":"oovMR0WZOnvIRn1xKEGPkyFhjkPM"

        $uuid = Uuid::uuid1();
        $token = $uuid->getHex();    //32位字符串方法

        $openid = $data['openid'];

        $user = User::firstOrCreate(['openid' => $openid])->toArray();

        //token缓存
        $data = [
            //'type' => User::LOGIN_TYPE_WEIXIN,//0微信登录,1手机登录
            //'openid' => $openid,
            'session_key' => $data['session_key'],
            'user_id' => $user['id'],
            //'phone' => !empty($user['phone']) ? $user['phone'] : '',
        ];
        if (UserToken::updateOrCreate(['user_id' => $user['id']], ['session_key' => $data['session_key'], 'token' => $token])) {
            return $token;
        }
        return false;

    }

    /**
     * @param $token
     * @param $phone
     * @param $loginType
     * @return bool
     */
    public static function bindPhone($token, $phone, $loginType = User::LOGIN_TYPE_WEIXIN)
    {
        $userToken = UserToken::whereToken($token)->first();
        if (!$userToken) {
            Log::error("bindPhonecan not find token:$token,phone:$phone");
            return false;
        }
        $userId = $userToken->user_id;
        $userToken->type = $loginType;
        $userToken->token = $token;
        $userToken->save();
        User::where(['user_id' => $userId])->update(['phone' => $phone]);
    }

    public static function getSessionKeyByToken($token)
    {
        $userInfo = self::getUserInfoByToken($token);
        return $userInfo['session_key'];
    }


    /**
     * @param $token
     * @return bool
     */
    public static function getUserInfoByToken($token)
    {
        $userToken = UserToken::whereToken($token)->first();
        if (!$userToken) {
            //Log::error("getUserInfoByToken can not find token:$token");
            return false;
        }
        $userId = $userToken->user_id;
        $userInfo = self::getUserByUserId($userId);
        if ($userInfo['phone']) {
            $userInfo['user_id'] = $userInfo['id'];
            $userInfo['session_key'] = $userToken->session_key;
            return self::$userInfo = $userInfo;
        }
        return false;
    }

    /**
     * @param $token
     * @return bool
     */
    public static function getPhoneByToken($token)
    {
        $userInfo = UserService::getUserInfoByToken($token);
        if ($userInfo && $userInfo['phone']) {
            return $userInfo['phone'];
        }
        return false;
    }

    public static function getOpenIdByToken($token)
    {
        $userInfo = UserService::getUserInfoByToken($token);
        if ($userInfo && $userInfo['openid']) {
            return $userInfo['openid'];
        }
        return false;
    }

    /**
     * 增加用户金额数量
     * @param $userId
     * @param $amount
     */
    public static function addUserBalance($userId, $amount)
    {
        $user = User::whereId($userId)->first();
        $userBalance = $user->user_balance;
        $userBalance += $amount;
        $user->user_balance = $userBalance;
        return $user->save();
    }

    /**
     * @param $userId
     * @return array|bool
     */
    public static function getUserByUserId($userId)
    {
        $user = User::whereId($userId)->first();
        return $user ? $user->toArray() : false;
    }

}