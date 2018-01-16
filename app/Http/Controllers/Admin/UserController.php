<?php
/**
 * Created by PhpStorm.
 * User: eme
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;


use App\Models\Feedbacks;
use App\Models\User;

class UserController extends BaseController
{
    public function list()
    {

        $users = User::getUserList();

        return view('admin.user.list',[
            'users'=>$users,
        ]);
    }

    public function feedback()
    {

        $feedbacks = Feedbacks::join('users',function($join){
            $join->on('users.id','=','user_id');
        })->select(['feedbacks.*','users.phone'])->orderBy('id')->get();

        return view('admin.user.feedbacks',[
            'feedbacks'=>$feedbacks,
        ]);

    }

}
