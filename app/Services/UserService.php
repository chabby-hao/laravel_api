<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;

class UserService extends BaseService
{

    
    /*'type'=>User::LOGIN_TYPE_WEIXIN,//0微信登录,1手机登录
        'openid' => $openid,
        'session_key' => $data['session_key'],
        'uid' => $user['id'],
        'phone' => !empty($user['phone']) ? $user['phone'] : '',*/
    public static $userInfo = [];

    public static function getUid()
    {
        if(!empty(static::$userInfo['uid'])){
            return static::$userInfo['uid'];
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
            'type' => User::LOGIN_TYPE_WEIXIN,//0微信登录,1手机登录
            'openid' => $openid,
            'session_key' => $data['session_key'],
            'uid' => $user['id'],
            'phone' => !empty($user['phone']) ? $user['phone'] : '',
        ];
        Cache::put($token, json_encode($data), 300);

        return $token;
    }

    public static function bindPhone($token, $phone)
    {
        $data = Cache::get($token);
        $data = json_decode($data, true);
        $data['phone'] = $phone;
        $data['type'] = User::LOGIN_TYPE_PHONE;

        $uid = $data['uid'];
        User::where(['id' => $uid])->update(['phone' => $phone]);

        Cache::put($token, json_encode($data), 120);
    }

    /**
     * @param $token
     * @return bool
     */
    public static function getUserInfoByToken($token)
    {
        $userInfo = Cache::get($token);
        Cache:
        $userInfo && $userInfo = json_decode($userInfo, true);
        self::$userInfo = $userInfo;
        return $userInfo;
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