<?php
/**
 * Created by PhpStorm.
 * User: eme
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;


use App\Libs\MyPage;
use App\Models\ChargeTasks;

class ChargeController extends BaseController
{
    public function list()
    {

        $paginate = ChargeTasks::join('users',function($join){
            $join->on('users.id','=','user_id');
        })->select(['charge_tasks.*','users.phone'])->orderByDesc('id')->paginate();


        return view('admin.charge.list',[
            'charges'=>$paginate->items(),
            'page_nav'=>MyPage::showPageNav($paginate),
        ]);
    }


}
