<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['phone', 'openid'];

    public static function getUserByOpenid($openid)
    {
        return self::where(['openid'=>$openid])->first()->toArray();
    }
}
