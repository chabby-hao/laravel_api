<?php
/**
 * Created by PhpStorm.
 * User: eme
 * Date: 2017/11/7
 * Time: 上午11:04
 */

namespace app\Services;

use App\Models\VerifyCode;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;

class VerifyCodeServices{


    //判断手机号是否有还未过期的验证码
    /**
     * @param $phone string  手机号
     * @param $is_toarray 是否转换为数组
     */
    public function codeIsExpired($phone,$toarray = true){
        $verifycodeModel = new VerifyCode();
        $code = $verifycodeModel->where('phone',$phone)->where('expire_at','>',time()-30*60)->get();
        if($toarray){
            $code = $code->toArray();
        }
        return $code;
    }
}