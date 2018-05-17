<?php

namespace App\Http\Controllers\Api;

use App\Libs\ErrorCode;
use App\Libs\Helper;
use App\Models\Appointments;
use App\Models\Battery;
use App\Models\Cabinets;
use App\Models\ChargeTasks;
use App\Models\DeviceInfo;
use App\Models\ReplaceNotifyLog;
use App\Models\ReplaceTasks;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\WelfareDevices;
use App\Models\WelfareUsers;
use App\Services\BoxService;
use App\Services\CabinetService;
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
        //$arr = json_decode($qr, true);


        $output = [];

        //判断是换电还是绑定电池型号
        if (preg_match('/http:\/\/www.vipcare.com\/qr.html#(\d+)/i', $qr, $match)) {
            //绑定电池
            //电池二维码格式  http://www.vipcare.com/qr.html#928676166346
            $udid = $match[1];
            $battery = Battery::whereUdid($udid)->first();
            if ($battery) {

                if(UserDevice::whereBatteryId($battery->battery_id)->first()){
                    return Helper::responeseError(ErrorCode::$batteryBindRepeat);
                }

                UserDevice::whereUserId($userId)->delete();//1个用户只能绑一个，所以这里比较简单粗暴
                UserDevice::create([
                    'user_id' => $userId,
                    'battery_id' => $battery->battery_id,
                ]);
                $output['type'] = 1;//绑电池
                return Helper::response($output);//绑定成功
            }
            return Helper::responeseError(ErrorCode::$batteryNotRegister);
        } elseif ($cabinetId = CabinetService::getCabinetIdByQr($qr)) {
            //换电,这里是柜子二维码,{"cabinetId":'1'}

            //是否绑定电池
            if (!$battery = UserService::getUserBattery($userId)) {
                return Helper::responeseError(ErrorCode::$notBindBattery);
            }

            //正在运维
            if (CabinetService::isOps($cabinetId)) {
                return Helper::responeseError(ErrorCode::$isOpsNow);
            }

            //检查当前柜子是否有未完成的任务，如果有需要等待前一个任务结束
            if (ReplaceService::checkProcessingTask($cabinetId)) {
                return Helper::responeseError(ErrorCode::$needWait);
            }

            //检查用户余额
            if (UserService::getAvailabelBalance($userId) <= 0) {
                return Helper::responeseError(ErrorCode::$balanceNotEnough);
            }

            //柜子是否可用
            if (!CabinetService::isCabinetUseful($cabinetId)) {
                return Helper::responeseError(ErrorCode::$cabinetUnuseful);
            }

            //是否有可换电的电池
            if (!CabinetService::hasAvailableBattery($cabinetId, $battery->battery_level)) {
                return Helper::responeseError(ErrorCode::$batteryNotEnough);
            }

            //开始一项新的换电任务
            ReplaceService::startReplaceBattery($userId, $cabinetId);

            $output['type'] = 0;//换电池
            $output['cabinetId'] = $cabinetId;
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

        if (!$battery = UserService::getUserBattery($userId)) {
            return Helper::responeseError(ErrorCode::$notBindBattery);
        }
        $batteryLevel = $battery->battery_level;

        $data = [];
        $data['appointmentId'] = 0;
        //是否已经预约
        if ($model = Appointments::whereUserId($userId)->whereCabinetId($cabinetId)->where('expired_at', '>', Carbon::now()->toDateTimeString())->first()) {
            $data['appointmentId'] = intval($model->id);
            $data['remain'] = Carbon::parse($model->expired_at)->getTimestamp() - time();
        }

        $data['batteryCount'] = CabinetService::getAvailableAppointmentBatteryCount($cabinetId, $batteryLevel);
        $data['address'] = CabinetService::getCabinetAddressById($cabinetId);

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

        $battery = UserService::getUserBattery($userId);
        if (!$battery) {
            return Helper::responeseError(ErrorCode::$notBindBattery);
        }

        //是否已经预约
        if (Appointments::whereUserId($userId)->whereCabinetId($cabinetId)->where('expired_at', '>', Carbon::now()->toDateTimeString())->first()) {
            return Helper::responeseError(ErrorCode::$appointmentExists);
        }

        $batteryLevel = $battery->battery_level;
        //检查是否可以预约
        if (!CabinetService::getAvailableAppointmentBatteryCount($cabinetId, $batteryLevel)) {
            return Helper::responeseError(ErrorCode::$batteryNotEnough);
        }

        //预约
        ReplaceService::appointment($userId, $cabinetId);

        return $this->responseOk();

    }

    public function getAdress()
    {

        if (!UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        $cabinetId = $this->checkRequireParams(['cabinetId'])['cabinetId'];


        $model = Cabinets::find($cabinetId);
        if (!$model) {
            return Helper::responeseError(ErrorCode::$cabinetUnuseful);
        }

        $data = [
            'cabinetNo' => $model->cabinet_no,
            'address' => $model->address,
        ];

        return Helper::response($data);

    }

    public function getStep()
    {
        if (!$userId = UserService::getUserId()) {
            return Helper::responeseError(ErrorCode::$tokenExpire);
        }

        $model = ReplaceTasks::whereUserId($userId)->orderByDesc('id')->first();
        if ($model && in_array($model->state, [ReplaceTasks::TASK_STATE_FAIL, ReplaceTasks::TASK_STATE_ABNORMAL])) {
            return Helper::responeseError(ErrorCode::$replaceFail);
        } elseif ($model) {
            $data['step'] = $model->step;
        } else {
            return Helper::responeseError(ErrorCode::$batteryNotEnough);
        }
        return Helper::response($data);
    }

    public function taskNotify(Request $request)
    {
        $inputRequire = ['cabinetNo', 'taskId', 'step', 'batteryId', 'timestamp', 'sign'];
        $input = $this->checkRequireParams($inputRequire, $request->input());
        if ($input instanceof Response) {
            return $input;
        }

        if (!RequestService::checkSign($input)) {
            return Helper::responeseError(ErrorCode::$errSign);
        }

        $cabinetNo = $input['cabinetNo'];
        $taskId = $input['taskId'];
        $step = intval($input['step']);
        $batteryId = $input['batteryId'];

        Log::debug('taskNotify receive data: ', $input);

        ReplaceNotifyLog::create([
            'cabinet_no'=>$cabinetNo,
            'task_id'=>$taskId,
            'step'=>$step,
            'battery_id'=>$batteryId,
        ]);

        if (!$task = ReplaceTasks::find($taskId)) {
            return Helper::responeseError(ErrorCode::$notFindTask);
        }

        if ($task->step > $step) {
            return Helper::response([
                'cabinetNo' => $cabinetNo,
                'step' => $step,
            ]);
        }

        //$step 0=扫码下发命令回执，10=放入旧电池，关闭柜门，20=放入新电池，关闭柜门，30=换电失败
        if ($step === 0) {
            $task->step = ReplaceTasks::STEP_INIT;
            $task->state = ReplaceTasks::TASK_STATE_PROCESSING;//收到命令，进行中
            $task->save();
        } elseif ($step === 10) {
            $task->step = ReplaceTasks::STEP_10;
            //第一步完成，解绑电池
            UserDevice::whereUserId($task->user_id)->whereBatteryId($task->battery_id1)->delete();
            $task->save();
        } elseif ($step === ReplaceTasks::STEP_20) {
            //结束换电
            $task->step = ReplaceTasks::STEP_20;
            $task->state = ReplaceTasks::TASK_STATE_COMPLETE;
            $task->battery_id2 = $batteryId;
            $task->save();
            ReplaceService::userCost($taskId);

            UserDevice::create([
                'battery_id'=>$batteryId,
                'user_id'=>$task->user_id,
            ]);
        } elseif ($step === 30) {
            $task->state = ReplaceTasks::TASK_STATE_FAIL;
            $task->save();
        }

        return Helper::response([
            'cabinetNo' => $cabinetNo,
            'step' => $step,
        ]);
    }

    public function list()
    {
        $userId = $this->checkUser();

        $tasks = ReplaceTasks::whereUserId($userId)->whereState(ReplaceTasks::TASK_STATE_COMPLETE)->get();

        $datas = [];
        if ($tasks) {
            foreach ($tasks as $task) {
                $data = [
                    'cost' => $task->actual_cost,
                    'costType' => '余额付款',
                    'createdAt' => $task->created_at->toDateTimeString(),
                    'cabinetNo' => CabinetService::getCabinetNoById($task->cabinet_id),
                    'address' => CabinetService::getCabinetAddressById($task->cabinet_id),
                ];
                $datas[] = $data;
            }
        }

        return Helper::response($datas);
    }

    public function cabinetList()
    {


        $data = [];
        $cabinets = Cabinets::get();
        foreach ($cabinets as $cabinet){
            $tmp = [
                'lat'=>floatval($cabinet->lat),
                'lng'=>floatval($cabinet->lng),
                'address'=>$cabinet->address,
                //'cabinetNo'=>$cabinet->cabinet_no,
                'cabinetId'=>$cabinet->id,
            ];
            $data[] = $tmp;
        }

        return Helper::response($data);

    }

}