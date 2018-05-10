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
use Illuminate\Support\Facades\Input;

class BatteryController extends BaseController
{
    public function list()
    {


        $where = [];
        if($deviceNo = Input::get('device_no')){
            $where['device_no'] = $deviceNo;
        }
        if($portNo = Input::get('port_no')){
            $where['port_no'] = $portNo;
        }

        $paginate = ChargeTasks::join('users',function($join){
            $join->on('users.id','=','user_id');
        })->where($where)->select(['charge_tasks.*','users.phone'])->orderByDesc('id')->paginate();


        return view('admin.charge.list',[
            'charges'=>$paginate->items(),
            'page_nav'=>MyPage::showPageNav($paginate),
        ]);
    }


}
