<?php

namespace App\Services;

use App\Libs\WxApi;
use App\Models\ChargeNotifyLog;
use App\Models\ChargeTasks;
use App\Models\DeviceInfo;
use App\Models\User;
use App\Models\WelfareUsers;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Log;
use Psy\Command\WhereamiCommand;

class ChargeService extends BaseService
{

    //const TEMPLATE_ID_AB = '8ySltzpdZn80ymIGD6-2N6vhQ1YFGbjRMZ0v8js22YA';

    const TEMPLATE_ID_END = 'kJuQgZvKVkuGr_uK16rrXg0NKYeLaoHWiEq9uvE7x14';

    const PER_MINUTE_CHARGE_PRICE = 0.01;//单位分钟扣除费用(元)

    const CLOSE_BOX_TIMEOUT = 120;//下发充电命令，多长时间关闭箱子(秒)

    const MAX_CHARGING_TIME = 3600 * 48;//最大充电时长

    /**
     * 是否正在充电
     */
    public static function isCharging()
    {

    }

    /**
     * 开始充电
     * @param $userId
     * @param $deviceNo
     * @param $portNo
     * @param $mode 0=充满（小时）
     * @return bool
     */
    public static function startCharge($userId, $deviceId, $mode, $formId)
    {
        $duration = $mode * 3600;
//        if ($mode == 4) {
//            //4小时，测试改成2分钟
//            $duration = 120;
//        }
        if (!$deviceModel = DeviceInfo::find($deviceId)) {
            Log::warning('deviceInfo not find deviceId:' . $deviceId);
            return false;
        }
        $deviceNo = $deviceModel->device_no;
        $portNo = $deviceModel->port_no;
        $chargeTaskMod = new ChargeTasks();
        $taskId = $chargeTaskMod->createTask($userId, $deviceNo, $portNo, $duration, $formId);
        if (!$taskId) {
            return false;
        }

        self::sendCmdToStartCharge($deviceNo, $portNo, $taskId);

        return $taskId;
    }

    public static function sendCmdToStartCharge($deviceNo, $portNo, $taskId)
    {
        Log::debug("start charge $deviceNo $portNo $taskId");
        DeviceService::sendChargingHash($deviceNo, $portNo, $taskId);
        return CommandService::sendCommandChargeStart($deviceNo, $portNo);
    }


    public static function beginCharingByTaskId($taskId)
    {
        $model = ChargeTasks::find($taskId);
        if ($model && $model->task_state == ChargeTasks::TASK_STATE_INIT) {
            $model->task_state = ChargeTasks::TASK_STATE_CHARGING;
            $model->begin_at = date('Y-m-d H:i:s');
            $expectTime = $model->expect_time;
            if ($expectTime) {
                $model->expect_end_at = date('Y-m-d H:i:s', strtotime("+$expectTime seconds"));
            }
            //if (BoxService::isOpen($model->device_no, $model->port_no)) {
            //关箱子
            if(DeviceService::isOldDevice($model->device_no)){
                BoxService::closeBox($model->device_no, $model->port_no);
            }
            //}
            ChargeNotifyLog::addLog($model->device_no, $model->port_no, 10);
            return $model->save();
        } elseif ($model) {
            return true;
        }
        return false;
    }

    /**
     * 结束充电
     * @param $device array|string    ['device_no'=>'123','port_no'=>'1']|$deviceId
     * @param int $state
     * @return bool
     */
    public static function endCharge($device, $state = ChargeTasks::TASK_STATE_COMPLETE, $sendCmd = true)
    {
        if (is_numeric($device)) {
            if (!$deviceModel = DeviceInfo::find($device)) {
                Log::warning('deviceInfo not find deviceId:' . $device);
                return false;
            }
            $deviceNo = $deviceModel->device_no;
            $portNo = $deviceModel->port_no;
        } elseif (is_array($device)) {
            $deviceNo = $device['device_no'];
            $portNo = $device['port_no'];
        } else {
            Log::error('device is not array|string');
            return false;
        }

        //Log::debug("end charge device_no: $deviceNo,port_no:$portNo");

        $model = ChargeTasks::where(['device_no' => $deviceNo, 'port_no' => $portNo])->orderBy('id', 'desc')->first();
        if (!$model) {
            return false;
        }
        //必须正在充电
        if ($model->task_state != ChargeTasks::TASK_STATE_CHARGING) {
            return false;
        }
        $begin = $model->begin_at;
        $beginTime = strtotime($begin);
        $model->actual_time = time() - $beginTime;
        $model->task_state = $state;
        $model->close_box = ChargeTasks::CLOSE_BOX_HAS_SENT;
        $model->save();


        //扣费逻辑
        self::_chargeCost($model->user_id, $model->actual_time, $model->id);

        //兼容老固件，所有关闭都发命令
        if(DeviceService::isOldDevice($deviceNo)){
            $sendCmd = true;
        }

        if ($sendCmd) {
            CommandService::sendCommandChargeEnd($deviceNo, $portNo);
        }
        //BoxService::openBox($deviceNo, $portNo);
        return true;
    }

    /**
     * 扣费
     * @param $userId
     * @param $chargeTime
     * @return int
     */
    private static function _chargeCost($userId, $chargeTime, $taskId)
    {
        if ($chargeTime > self::MAX_CHARGING_TIME) {
            $chargeTime = self::MAX_CHARGING_TIME;
        }
        $minutes = floor($chargeTime / 60);
        $costs = $minutes * self::PER_MINUTE_CHARGE_PRICE;
        $charge = ChargeTasks::find($taskId);
        $charge->user_cost = $costs;//需要支付$costs

        if (WelfareUsers::join('welfare_devices', function ($join) {
            /** @var JoinClause $join */
            $join->on('welfare_users.card_id', '=', 'welfare_devices.card_id');
        })->whereUserId($userId)->whereDeviceNo($charge->device_no)->first()
        ) {
            //福利卡用户
            $charge->cost_type = ChargeTasks::COST_TYPE_CARD;
            $charge->actual_cost = 0;//实际支付0
        } else {
            $user = User::find($userId);
            //打4折
            $costs = $costs * 0.4;
            if ($user->user_balance > 0) {
                //$charge->cost_type = ChargeTasks::COST_TYPE_BALANCE;
                $field = 'user_balance';
            } else {
                //$charge->cost_type = ChargeTasks::COST_TYPE_PRESNET;
                $field = 'present_balance';
            }
            $charge->cost_type = ChargeTasks::COST_TYPE_DISCOUNT_40;//4折优惠
            $charge->actual_cost = $costs;
            //扣款
            User::chargeCost($userId, $costs, $field);
        }
        //更新充电任务
        return $charge->save();
    }

    /**
     * @param $device
     * @return bool
     */
    public static function endChargeByUser($device)
    {
        $end = self::endCharge($device, ChargeTasks::TASK_STATE_USER_END);
        $taskId = ChargeTasks::getLastTaskIdByDevice($device['device_no'], $device['port_no']);
        return $end ? self::sendEndMessage($taskId) : false;
    }

    /**
     * 时间结束自动中断停电
     * @param $deviceNo
     * @param $portNo
     * @return bool
     */
    public static function endChargeByTimeOver($deviceNo, $portNo)
    {
        $end = self::endCharge(['device_no' => $deviceNo, 'port_no' => $portNo], ChargeTasks::TASK_STATE_TIME_END);
        $taskId = ChargeTasks::getLastTaskIdByDevice($deviceNo, $portNo);
        return $end ? self::sendEndMessage($taskId) : false;
    }

    /**
     * @param $deviceNo
     * @param $portNo
     * @return bool
     */
    public static function chargeHaltComplete($deviceNo, $portNo)
    {
        $device = ['device_no' => $deviceNo, 'port_no' => $portNo];
        $state = ChargeTasks::TASK_STATE_COMPLETE;
        if(self::endCharge($device, $state, false)){
            $taskId = ChargeTasks::getLastTaskIdByDevice($deviceNo, $portNo);
            return self::sendEndMessage($taskId);
        }
        return false;
    }

    /**
     * @param $deviceNo
     * @param $portNo
     * @return bool
     */
    public static function chargeHaltAbnormal($deviceNo, $portNo)
    {
        $device = ['device_no' => $deviceNo, 'port_no' => $portNo];
        $state = ChargeTasks::TASK_STATE_END_ABMORMAL;
        if(self::endCharge($device, $state, false)){
            $taskId = ChargeTasks::getLastTaskIdByDevice($deviceNo, $portNo);
            return self::sendEndAbMessage($taskId);
        }
        return false;
    }

    /**
     * 获取当前充电任务信息
     * @param $userId
     * @return array|bool
     */
    public static function  getLastTaskInfo($userId)
    {

        $model = ChargeTasks::getLastTaskByUserId($userId);
        if (!$model) {
            return false;
        }
        $data = [
            'status' => 0,//正在冲
            'mins' => 0,//分钟
            'seconds'=>0,//秒
            'task_id' => $model->id,
        ];

        if ($model->task_state == ChargeTasks::TASK_STATE_END_ABMORMAL) {
            $data['status'] = 1;//异常终止
        } elseif (in_array($model->task_state, ChargeTasks::getFinishStateMap())) {
            $data['status'] = 2;//充电完成
        } elseif ($model->task_state == ChargeTasks::TASK_STATE_TIMEOUT && $model->close_box == ChargeTasks::CLOSE_BOX_HAS_SENT) {
            $data['status'] = 4;//舒适化，未通电，但已超时
        } elseif ($model->task_state == ChargeTasks::TASK_STATE_INIT) {
            $data['status'] = 3;//初始化，还没通电
        } else {
            $begin = strtotime($model->begin_at);
            $time = time() - $begin;
            $mins = floor($time / 60);
            $data['mins'] = sprintf('%02s', $mins);
            $data['seconds'] = sprintf('%02s', $time);
        }

        return $data;
    }

    /**
     * 开始充电
     * @param $taskId
     * @return bool
     */
    public static function powerOn($taskId)
    {
        if (!$model = ChargeTasks::find($taskId)) {
            return false;
        }
        Log::debug('task info ' . $model->toJson() . ' is charging now...');
        //开始充电
        $duration = $model->duration;
        $model->task_state = ChargeTasks::TASK_STATE_CHARGING;
        $model->begin_at = date('Y-m-d H:i:s');
        $model->expect_end_at = date('Y-m-d H:i:s', strtotime("+$duration seconds"));
        return $model->save();
    }

    /**
     * @param $userId
     * @return bool|int|mixed
     */
    public static function getUnfinishTaskIdByUserId($userId)
    {
        $model = ChargeTasks::whereUserId($userId)->orderByDesc('id')->first();
        if (!$model || $model->task_state != ChargeTasks::TASK_STATE_CHARGING) {
            return false;
        }
        return $model->id;
    }

    /**
     * 结束充电提醒
     * @param $taskId
     * @return bool
     */
    public static function sendEndAbMessage($taskId)
    {
        $task = ChargeTasks::find($taskId);
        if (!$task) {
            return false;
        }
        $data = [
            'template_id' => self::TEMPLATE_ID_END,
            'data' => [
                'keyword1' => ['value' => '￥' . $task->actual_cost, 'color' => '#173177'],
                'keyword2' => ['value' => floor($task->actual_time / 60) . '分钟', 'color' => '#173177'],
                'keyword3' => ['value' => '充电过程被意外中断，请到充电棚查看充电器连接情况', 'color' => '#173177'],
            ],
            "page" => "pages/index/index",
        ];
        return self::sendMessageToUser($taskId, $data);
    }

    /**
     * 结束充电提醒
     * @param $taskId
     * @return bool
     */
    public static function sendEndMessage($taskId)
    {
        $task = ChargeTasks::find($taskId);
        if (!$task) {
            return false;
        }

        $userId = $task->user_id;
        $userBalance = UserService::getUserBalance($userId);
        if ($userBalance < 0) {
            //欠费提醒
            $desc = '您已欠费' . abs($userBalance) . '元，为了不影响下次充电，请及时充值';
        } elseif($task->cost_type == ChargeTasks::COST_TYPE_DISCOUNT_40) {
            $desc = '已享受4折优惠，为您省下了￥' . number_format($task->user_cost - $task->actual_cost, 2) . '元';
        } else{
            $desc = '无';
        }

        $data = [
            'template_id' => self::TEMPLATE_ID_END,
            'data' => [
                'keyword1' => ['value' => '￥' . $task->actual_cost, 'color' => '#173177'],
                'keyword2' => ['value' => floor($task->actual_time / 60) . '分钟', 'color' => '#173177'],
                'keyword3' => ['value' => $desc, 'color' => '#173177'],
            ],
            "page" => "pages/index/index",
        ];
        return self::sendMessageToUser($taskId, $data);
    }

    public static function sendMessageToUser($taskId, array $data)
    {
        $model = ChargeTasks::find($taskId);
        if (!$model) {
            return false;
        }
        $formId = $model->form_id;
        $openId = User::getOpenIdById($model->user_id);
        $default = [
            'touser' => $openId,
            'form_id' => $formId,
        ];
        $data = array_merge($data, $default);
        $wxapi = new WxApi();
        return $wxapi->sendMessage($data);
    }

    /**
     * 获取充电列表
     * @param $userId
     * @return array
     */
    public static function chargeList($userId)
    {
        $models = ChargeTasks::whereUserId($userId)->whereIn('task_state', ChargeTasks::getFinishStateMap())->orderBy('id', 'desc')->get();
        $ret = [];
        if ($models) {
            foreach ($models as $model) {
                $tmp = [];
                $tmp['begin_at'] = $model->begin_at;
                $tmp['minutes'] = floor($model->actual_time / 60);
                $tmp['pay_amount'] = $model->user_cost;
                $tmp['actual_amount'] = $model->actual_cost;
                $tmp['cost_type_t'] = ChargeTasks::getCostTypeMap($model->cost_type);
                //兼容福利卡支付
                $tmp['cost_type'] = $model->cost_type;
                /*if($costType == ChargeTasks::COST_TYPE_CARD){
                    $costType = ChargeTasks::COST_TYPE_PRESNET;
                }*/
                //$tmp['cost_type'] = $costType;
                $ret[] = $tmp;
            }
        }
        return $ret;
    }

}