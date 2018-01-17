<?php
//第三方请求签名服务
namespace App\Services;

use App\Libs\Helper;
use App\Models\Admins;

class AdminService extends BaseService
{

    public static function addAdmin($name, $pwd)
    {
        try {
            $admin = new Admins();
            $admin->name = $name;
            $admin->pwd = self::_encrypt($pwd);
            $res = $admin->save();
        } catch (\Exception $e) {
            \Log::error('add admin db error : ' . $e->getMessage());
            return false;
        }
        return $res;
    }

    public static function login($name, $pwd)
    {
        $admin = Admins::whereName($name)->first();
        if($admin){
            $pwd2= $admin->pwd;
            $pwd1 = self::_encrypt($pwd);
            if($pwd1 === $pwd2){
                session()->put('is_login', 1);
                session()->put('admin_name', $name);
                session()->put('admin_id', $admin->id);
                session()->save();
                return true;
            }
        }

        return false;
    }

    public static function logout()
    {
        session()->flush();
        return session()->save();
    }

    private static function _encrypt($pwd)
    {
        return md5($pwd);
    }

}