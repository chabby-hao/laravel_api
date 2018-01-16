<?php
/**
 * Created by PhpStorm.
 * User: eme
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;


use App\Models\ChargeTasks;

class ChargeController extends BaseController
{
    public function list()
    {

        $charges = ChargeTasks::join('users',function($join){
            $join->on('users.id','=','user_id');
        })->select(['charge_tasks.*','users.phone'])->orderByDesc('id')->get();


        return view('admin.charge.list',[
            'charges'=>$charges,
        ]);
    }


}
