<?php

namespace App\Http\Controllers\Api;

use App\Libs\ErrorCode;
use App\Libs\Helper;
use App\Models\Appointments;
use App\Models\Battery;
use App\Models\ChargeTasks;
use App\Models\DeviceInfo;
use App\Models\ReplaceTasks;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\WelfareDevices;
use App\Models\WelfareUsers;
use App\Services\BoxService;
use App\Services\ChargeService;
use App\Services\DeviceService;
use App\Services\ReplaceService;
use App\Services\RequestService;
use App\Services\UserService;
use Carbon\Carbon;
use function Hprose\Future\error;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ReplaceBatteryController extends Controller
{

    public function checkQrCode(Request $request)
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }
        $data = $this->checkRequireParams(['qr']);
        $qr = $data['qr'];

        $output = [];

        //判断是换电还是绑定电池型号
        if (preg_match('/http:\/\/www.vipcare.com\/qr.html#(\d+)/i', $qr, $match)) {
            //绑定电池
            //电池二维码格式  http://www.vipcare.com/qr.html#928676166346
            $udid = $match[1];
            $battery = Battery::whereUdid($udid)->first();
            if ($battery) {
                UserDevice::whereUserId($userId)->delete();//1个用户只能绑一个，所以这里比较简单粗暴
                UserDevice::create([
                    'user_id' => $userId,
                    'battery_id' => $battery->id,
                ]);
                $output['type'] = 1;//绑电池
                return Helper::response($output);//绑定成功
            }
            return Helper::responeseError(ErrorCode::$batteryNotRegister);
        } elseif ($arr = json_decode($qr, true) && isset($arr['cabinetId'])) {
            //换电,这里是柜子二维码,{"cabinetId":'02100434'}
            $cabinetId = $arr['cabinetId'];

            //检查用户余额
            if (UserService::getAvailabelBalance($userId) <= 0) {
                return Helper::responeseError(ErrorCode::$balanceNotEnough);
            }

            //柜子是否可用

            //是否有可换电的电池

            //开始一项新的换电任务
            ReplaceService::startReplaceBattery($userId, $cabinetId);

            $output['type'] = 0;//换电池
            return Helper::response($output);
        } else {
            return Helper::responeseError(ErrorCode::$qrCodeNotFind);//二维码有误
        }

    }


    public function appointmentStatus(Request $request)
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }
        $input = $this->checkRequireParams(['cabinetId']);
        $cabinetId = $input['cabinetId'];

        $data = [];
        $data['appointmentId'] = 0;
        $data['batteryCount'] = 0;
        //是否已经预约
        if ($model = Appointments::whereUserId($userId)->whereCabinetId($cabinetId)->where('expired_at', '>', Carbon::now()->toDateTimeString())->first()) {
            $data['appointmentId'] = intval($model->id);
        }

        $data['batteryCount'] = mt_rand(0, 10);

        return Helper::response($data);
    }

    public function cancelAppointment(Request $request)
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }
        $input = $this->checkRequireParams(['appointmentId']);
        $id = $input['appointmentId'];

        if ($model = Appointments::find($id)) {
            $model->delete();
        }

        return $this->responseOk();
    }

    public function appointment(Request $request)
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }
        $input = $this->checkRequireParams(['cabinetId']);
        $cabinetId = $input['cabinetId'];

        //检查是否可以预约
        if (mt_rand(0, 1) === 1) {
            return Helper::responeseError(ErrorCode::$batteryNotEnough);
        }

        //是否已经预约
        if (Appointments::whereUserId($userId)->whereCabinetId($cabinetId)->where('expired_at', '>', Carbon::now()->toDateTimeString())->first()) {
            return Helper::responeseError(ErrorCode::$appointmentExists);
        }


        //预约
        ReplaceService::appointment($userId, $cabinetId);

        return $this->responseOk();

    }

    public function getAdress()
    {

        if(!UserService::getUserId()){
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        $cabinetId = $this->checkRequireParams(['cabinetId'])['cabinetId'];


        $data = [
            'cabinetNo' => '021000019',
            'address' => '华山公寓1号换电柜',
        ];

        return Helper::response($data);

    }

    public function getStep()
    {
        if(!$userId = UserService::getUserId()){
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        $data = [
            'finish'=>0,//是否可以结束换电，1=可以，0=不可以
        ];

        $model = ReplaceTasks::whereUserId($userId)->orderByDesc('id')->first();
        if($model){
            $data['step'] = $model->step;
            if($data['step'] == ReplaceTasks::STEP_20){
                $data['finish'] = 1;
            }
        }else{
            return Helper::responeseError(ErrorCode::$batteryNotEnough);
        }
        return Helper::response($data);
    }

}