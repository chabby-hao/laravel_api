<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $phone
 * @property string|null $openid
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float|null $user_balance 用户余额
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUserBalance($value)
 * @property float|null $present_balance 赠送金额余额
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePresentBalance($value)
 * @property int|null $ops 是否可以运维，0=不可，1=可
 * @property int|null $replace 是否可以换电，0=不可，1-可
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereOps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereReplace($value)
 */
class User extends Model
{
    //
    const LOGIN_TYPE_WEIXIN = 0;
    const LOGIN_TYPE_PHONE = 1;

    protected $table = 'users';
    protected $primaryKey = 'id';
//    protected $fillable = ['phone', 'openid'];
    protected $guarded = [];

    public static function getUserByOpenid($openid)
    {
        return self::where(['openid'=>$openid])->first()->toArray();
    }

    public static function getOpenIdById($userId)
    {
        $model = self::find($userId);
        return $model ? $model->openid : false;
    }

    public static function chargeCost($userId, $cost, $field = 'user_balance')
    {
        Log::info("charge cost user_id $userId cost $field $cost");
        return self::find($userId)->decrement($field, $cost);
    }

    public static function getUserList()
    {
        $users = self::where('phone','<>','')->orderByDesc('id')->get();
        return $users;
    }

}
