<?php
/**
 * Created by PhpStorm.
 * User: chabby
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;

use App\Models\ChargeTasks;
use App\Models\DeviceCostDetail;
use App\Models\DeviceInfo;
use App\Services\AdminService;
use App\Services\CommandService;
use App\Services\DeviceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class HomeController extends BaseController
{

    public function index(Request $request)
    {

        if($request->isXmlHttpRequest() || $request->input('a') == 1){

            $date = Carbon::now()->toDateString();
            $model = DeviceCostDetail::whereDate($date)->first();
            $today = [];
            if($model){
                $today['charge_times'] = $model->charge_times;
                $today['electric_quantity'] = $model->electric_quantity;
                $today['charge_duration'] = $model->charge_duration;
                $today['user_cost_amount'] = $model->user_cost_amount;
                $today['user_count'] = $model->user_count;
                $today['shared_amount'] = $model->shared_amount;
            }

            $monthStart = Carbon::now()->startOfMonth()->toDateString();
            $monthEnd = Carbon::now()->endOfMonth()->toDateString();

            $month = [];


            $month['charge_times'] = DeviceCostDetail::whereBetween('date',[$monthStart, $monthEnd])->sum('charge_times');
            $month['electric_quantity'] = DeviceCostDetail::whereBetween('date',[$monthStart, $monthEnd])->sum('electric_quantity');
            $month['charge_duration'] = DeviceCostDetail::whereBetween('date',[$monthStart, $monthEnd])->sum('charge_duration');
            $month['user_cost_amount'] = DeviceCostDetail::whereBetween('date',[$monthStart, $monthEnd])->sum('user_cost_amount');
            $month['user_count'] = DeviceCostDetail::whereBetween('date',[$monthStart, $monthEnd])->sum('user_count');
            $month['shared_amount'] = DeviceCostDetail::whereBetween('date',[$monthStart, $monthEnd])->sum('shared_amount');

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

            $cdpCount = DeviceInfo::where('lat','>',0)->selectRaw('count(distinct device_no) as mycount')->value('mycount');

            $cdkCount = DeviceInfo::where('lat','>',0)->count();

            $userCount = ChargeTasks::selectRaw('count(distinct user_id) as mycount')->value('mycount');

            return $this->_outPut([
                'cdpCount'=>$cdpCount,
                'cdkCount'=>$cdkCount,
                'userCount'=>$userCount,
            ]);

        }

        return view('admin.home.show');
    }

}
