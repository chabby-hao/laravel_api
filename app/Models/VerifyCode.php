<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyCode extends Model
{
    //

    protected $table = 'verify_code';
    protected $primaryKey = 'id';
    protected $fillable = ['phone', 'code'];

    /**
     * @param $phone
     * @param $code
     * @return array
     */
    public static function getByPhoneAndCode($phone, $code)
    {
        $verifyCode = self::where(['phone'=>$phone,'code'=>$code])->first()->toArray();
        if($verifyCode && $verifyCode['expire_at'] > time()){
            return $verifyCode;
        }else{
            return [];
        }
    }

}
