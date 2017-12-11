<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 * @property float|null $user_balance 用户余额\
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUserBalance($value)
 */
class User extends Model
{
    //
    const LOGIN_TYPE_WEIXIN = 0;
    const LOGIN_TYPE_PHONE = 1;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['phone', 'openid'];

    public static function getUserByOpenid($openid)
    {
        return self::where(['openid'=>$openid])->first()->toArray();
    }

    public static function getOpenIdById($userId)
    {
        $model = self::find($userId);
        return $model ? $model->openid : false;
    }

    public static function charging($userId, $cost)
    {
        return self::find($userId)->decrement('user_balance', $cost);
    }
}
