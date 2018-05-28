<?php
/**
 * Created by PhpStorm.
 * User: chabby
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;


use App\Libs\MyPage;
use App\Models\Battery;
use App\Models\ChargeTasks;
use App\Services\BatteryService;
use Illuminate\Support\Facades\Input;

class BatteryController extends BaseController
{
    public function list()
    {


        $paginate = Battery::leftJoin('user_device',function($join){
            $join->on('user_device.battery_id','=','battery.battery_id');
        })->leftJoin('users',function($join){
            $join->on('users.id','=','user_device.user_id');
        })->select(['battery.*','users.phone'])->orderByDesc('created_at')->paginate();

        $datas = $paginate->items();

        foreach ($datas as $data){
            $data->belong = '';

            if($data->phone){
                $data->belong = '用户-' . $data->phone;
            }elseif($cabDoor = BatteryService::getCabinetDoorNoByBatteryId($data->battery_id)){
                $data->belong = '柜-' . BatteryService::getCabinetDoorNoByBatteryId($data->battery_id);
            }else{
                $data->belong = '维护人员维护中';
            }
        }

        return view('admin.battery.list',[
            'datas'=>$datas,
            'page_nav'=>MyPage::showPageNav($paginate),
        ]);
    }


}
