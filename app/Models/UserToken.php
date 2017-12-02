<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserToken
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $token
 * @property string|null $session_key
 * @property string|null $openid
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserToken whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserToken whereSessionKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserToken whereUserId($value)
 * @mixin \Eloquent
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $type 0-微信，1-手机
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserToken whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserToken whereUpdatedAt($value)
 */
class UserToken extends Model
{


    protected $table = 'user_token';
    protected $primaryKey = 'id';
    //protected $fillable = ['*'];
    protected $guarded = [];

}
