<?php
/**
 * Created by PhpStorm.
 * User: chabby
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;

use App\Models\Admins;
use App\Models\ChargeTasks;
use App\Models\DeviceCostDetail;
use App\Models\DeviceInfo;
use App\Services\AdminService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends BaseController
{

    public function index(Request $request)
    {

        if($request->isXmlHttpRequest() || $request->input('a') == 1){

            $date = Carbon::now()->toDateString();
            $model = $this->getModel();
            $rs = $model->where('date', $date)->get();
            $today = [];
            $today['charge_times'] = 0;
            $today['electric_quantity'] = 0;
            $today['charge_duration'] = 0;
            $today['user_cost_amount'] = 0;
            $today['user_count'] = 0;
            $today['shared_amount'] = 0;
            if ($rs) {

                foreach ($rs as $model) {
                    $today['charge_times'] += $model->charge_times;
                    $today['electric_quantity'] += $model->electric_quantity;
                    $today['charge_duration'] += $model->charge_duration;
                    $today['user_cost_amount'] += $model->user_cost_amount;
                    $today['user_count'] += $model->user_count;
                    $today['shared_amount'] += $model->shared_amount;
                }
            }

            $today['electric_quantity'] = number_format($today['electric_quantity'], 2);
            $today['charge_duration'] = number_format($today['charge_duration'], 2);
            $today['user_cost_amount'] = number_format($today['user_cost_amount'], 2);
            $today['shared_amount'] = number_format($today['shared_amount'], 2);

            $monthStart = Carbon::now()->startOfMonth()->toDateString();
            $monthEnd = Carbon::now()->endOfMonth()->toDateString();

            $month = [];


            $month['charge_times'] = $this->getModel()->whereBetween('date',[$monthStart, $monthEnd])->sum('charge_times');
            $month['electric_quantity'] = $this->getModel()->whereBetween('date',[$monthStart, $monthEnd])->sum('electric_quantity');
            $month['charge_duration'] = $this->getModel()->whereBetween('date',[$monthStart, $monthEnd])->sum('charge_duration');
            $month['user_cost_amount'] = $this->getModel()->whereBetween('date',[$monthStart, $monthEnd])->sum('user_cost_amount');
            $month['user_count'] = $this->getModel()->whereBetween('date',[$monthStart, $monthEnd])->sum('user_count');
            $month['shared_amount'] = $this->getModel()->whereBetween('date',[$monthStart, $monthEnd])->sum('shared_amount');


            return $this->_outPut([
                'today'=>$today,
                'month'=>$month,
            ]);

        }

        return view('admin.home.index' );
    }

    public function show(Request $request)
    {

        if($request->isXmlHttpRequest() || $request->input('a') == 1) {

            $deviceNos = AdminService::getCurrentDeviceNos();

            $deviceNosInt = AdminService::getCurrentDeviceNos(true);

            $model = DeviceInfo::where([]);
            if($deviceNos){
                $model->whereIn('device_no', $deviceNos);
            }
            $cdpCount = $model->where('lat','>',0)->selectRaw('count(distinct device_no) as mycount')->value('mycount');

            $model = DeviceInfo::where([]);
            if($deviceNos){
                $model->whereIn('device_no', $deviceNos);
            }
            $cdkCount = $model->where('lat','>',0)->count();

            $model = ChargeTasks::where([]);
            if($deviceNosInt){
                $model->whereIn('device_no', $deviceNosInt);
            }
            $userCount = $model->selectRaw('count(distinct user_id) as mycount')->value('mycount');

            return $this->_outPut([
                'cdpCount'=>$cdpCount,
                'cdkCount'=>$cdkCount,
                'userCount'=>$userCount,
            ]);

        }

        return view('admin.home.show');
    }

    public function detailData(Request $request)
    {
        $where = [];
        $model = $this->getModel();
        if($deviceNo = $request->input('device_no')){
            $where['device_no'] = $deviceNo;
            $model->where($where);
        }

        if($date = $request->input('date')){
            $model->where('date',$date);
        }

        $devices = $model->orderByDesc('date')
            ->groupBy('date')
            ->selectRaw('date,sum(shared_amount) as shared_amount, sum(device_cost_amount) as device_cost_amount, sum(user_cost_amount) as user_cost_amount, sum(charge_times) as charge_times, sum(electric_quantity) as electric_quantity, sum(charge_duration) as charge_duration, sum(user_count) as user_count')
            ->paginate();

        $datas = $devices->items();
        /** @var DeviceCostDetail $data */
        $list = [];
        foreach ($datas as $data){
            $list[] = [
                'date'=>$data->date,
                //'device_no'=>$data->device_no,
                'shared_amount'=>$data->shared_amount,
                'device_cost_amount'=>$data->device_cost_amount,
                'user_cost_amount'=>$data->user_cost_amount,
                'charge_times'=>$data->charge_times,
                'electric_quantity'=>$data->electric_quantity,
                'charge_duration'=>$data->charge_duration,
                'user_count'=>$data->user_count,
            ];
        }

        return $this->_outPut(['list'=>$list,'lastPage'=>$devices->lastPage()]);
    }

    public function dailyDetail()
    {
        return view('admin.home.dailydetail');
    }

    public function monthDetail()
    {
        return view('admin.home.monthdetail');
    }

    public function detailDataByMonth(Request $request)
    {
        $where = [];
        $model = $this->getModel();
        if($deviceNo = $request->input('device_no')){
            $where['device_no'] = $deviceNo;
            $model->where($where);
        }

        if($date = $request->input('date')){
            $model->whereRaw("substr(date,1,7) = '$date'");
        }


        $devices = $model
            ->groupBy(DB::raw("substr(date,1,7)"))
            ->orderByDesc('date')
            ->selectRaw('substr(date,1,7) as date,sum(shared_amount) as shared_amount, sum(device_cost_amount) as device_cost_amount, sum(user_cost_amount) as user_cost_amount, sum(charge_times) as charge_times, sum(electric_quantity) as electric_quantity, sum(charge_duration) as charge_duration, sum(user_count) as user_count')
            ->paginate();

        $datas = $devices->items();

        /** @var DeviceCostDetail $data */
        $list = [];
        foreach ($datas as $data){
            $list[] = [
                'date'=>$data->date,
                'shared_amount'=>$data->shared_amount,
                'device_cost_amount'=>$data->device_cost_amount,
                'user_cost_amount'=>$data->user_cost_amount,
                'charge_times'=>$data->charge_times,
                'electric_quantity'=>$data->electric_quantity,
                'charge_duration'=>$data->charge_duration,
                'user_count'=>$data->user_count,
            ];
        }

        return $this->_outPut(['list'=>$list,'lastPage'=>$devices->lastPage()]);
    }

    public function deviceNoList()
    {
        if(AdminService::getCurrentUserType() === Admins::USER_TYPE_ADMIN){
            $list = DeviceInfo::getAllDeviceNo();
        }else{
            $list = AdminService::getCurrentDeviceNos();
        }

        return $this->_outPut(['list'=>$list]);
    }

    protected function getModel()
    {
        $model = DeviceCostDetail::where([]);
        if($deviceNos = AdminService::getCurrentDeviceNos()){
            $model->whereIn('device_no', $deviceNos);
        }
        return $model;
    }

}
