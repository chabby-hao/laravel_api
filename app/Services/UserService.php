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
        Log::debug('getOpenid:' . $json);
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
//        $data = [
//            //'type' => User::LOGIN_TYPE_WEIXIN,//0微信登录,1手机登录
//            //'openid' => $openid,
//            'session_key' => $data['session_key'],
//            //'user_id' => $user['id'],
//            'token'=>$token,
//            //'phone' => !empty($user['phone']) ? $user['phone'] : '',
//        ];
        $update = [
            'token' => $token,
            'session_key' => $data['session_key'],
            'openid' => $openid,
        ];
        if ($userToken = UserToken::updateOrCreate(['user_id' => $user['id']], $update)) {
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
        $user = User::wherePhone($phone)->first();
        if ($user && $user->id == $userId) {
            $userToken->type = $loginType;
            $userToken->save();
        } elseif ($user && $user->id != $userId) {
            $userTokenOld = UserToken::whereUserId($user->id)->first();
            if ($userTokenOld) {
                $userTokenOld->type = $loginType;
                $userTokenOld->user_id = $user->id;
                $userTokenOld->openid = $userToken->openid;
                $userTokenOld->save();
                Log::notice('delete userToken : ' . $userToken->toJson());
                $userToken->delete();
            } else {
                $userToken->type = $loginType;
                $userToken->user_id = $user->id;
                $userToken->save();
            }

            $user->openid = $userToken->openid;
            $user->save();
            if ($user2 = User::find($userId)) {
                if ($user2->user_balance == 0) {
                    Log::notice('delete user : ' . $user2->toJson());
                    $user2->delete();
                }
            }
        } else {
            //正常登录
            if ($user3 = User::whereOpenid($userToken->openid)->first()) {
                //已注册
                if ($user3->phone) {
                    $user3 = User::create([
                        'openid' => $userToken->openid,
                        'phone' => $phone,
                    ]);
                } else { //未注册
                    $user3->phone = $phone;
                    $user3->save();
                }
            } else { //切换账号场景
                $user3 = User::create([
                    'openid' => $userToken->openid,
                    'phone' => $phone,
                ]);
            }
            UserToken::updateOrCreate(['user_id' => $user3->id], [
                'openid' => $userToken->openid,
                'type' => $loginType,
                'token' => $token,
                'session_key' => $userToken->session_key,
            ]);
            //User::create(['id' => $userId])->update(['phone' => $phone, 'openid' => $userToken->openid]);
        }
        return true;
    }

    public static function getSessionKeyByToken($token)
    {
        $userToken = UserToken::whereToken($token)->first();
        if (!$userToken) {
            Log::debug('getSessionKey token invalid :' . $token);
            return false;
        }
        return $userToken->session_key;
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
            //$userInfo['session_key'] = $userToken->session_key;
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
        Log::debug('getPhoneByToken error with token:' . $token);
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

    public static function getUserBalance($userId)
    {
        $user = self::getUserByUserId($userId);
        return $user ? floatval($user['user_balance']) : 0;
    }


}