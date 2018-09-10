<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Admins
 *
 * @property int $id
 * @property int|null $name
 * @property int|null $pwd
 * @property int|null $user_type
 * @property string|null $user_config
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admins whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admins whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admins whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admins wherePwd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admins whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admins whereUserConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admins whereUserType($value)
 */
class Admins extends Model
{
    protected $guarded = [];

    const USER_TYPE_ADMIN = 0;//管理员

    const USER_TYPE_CHANNEL = 1;//渠道商

    public static function getUserType($type = null)
    {
        $map = [
            self::USER_TYPE_ADMIN => '管理员',
            self::USER_TYPE_CHANNEL => '渠道商',
        ];
        return $type === null ? $map : $map[$type];
    }


}
