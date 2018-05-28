<?php
/**
 * Created by PhpStorm.
 * User: chabby
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;


use App\Libs\MyPage;
use App\Models\ChargeTasks;
use App\Models\ReplaceTasks;
use Illuminate\Support\Facades\Input;

class ReplaceController extends BaseController
{
    public function list()
    {

        $where = [];

        $paginate = ReplaceTasks::join('users',function($join){
            $join->on('users.id','=','user_id');
        })->join('cabinets',function($join){
            $join->on('cabinets.id','=','cabinet_id');
        })->where($where)->select(['replace_tasks.*','users.phone','cabinets.cabinet_no'])->orderByDesc('id')->paginate();


        return view('admin.replace.list',[
            'datas'=>$paginate->items(),
            'page_nav'=>MyPage::showPageNav($paginate),
        ]);
    }


}
