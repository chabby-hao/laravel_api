<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyCode extends Model
{
    //

    protected $table = 'verify_code';
    protected $primaryKey = 'id';
    protected $fillable = ['phone', 'code','expire_at'];

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


//    public static function saveVerifyCode($data){
//        if(!empty($data)){
//            $id= self::table('')
//            return $id;
//        }
//
//    }

    public function codeIsExpired($phone,$toarray = true){
        $verifycodeModel = new VerifyCode();
        $code = $verifycodeModel->where('phone',$phone)->where('expire_at','>',time()-30*60)->get();
        if($toarray){
            $code = $code->toArray();
        }
        return $code;
    }

    public function insertData($data){
        $id = $this->save($data);
        return $id;
    }
}
