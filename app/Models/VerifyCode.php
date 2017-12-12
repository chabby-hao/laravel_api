<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VerifyCode
 *
 * @property int $id
 * @property string $phone
 * @property string $code
 * @property \Carbon\Carbon $created_at
 * @property int $expire_at 过期时间
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VerifyCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VerifyCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VerifyCode whereExpireAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VerifyCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VerifyCode wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VerifyCode whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
        $verifyCode = self::where(['phone'=>$phone,'code'=>$code])->first();
        if($verifyCode && $verifyCode->expire_at > time()){
            return $verifyCode->toArray();
        }else{
            return [];
        }
    }

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
