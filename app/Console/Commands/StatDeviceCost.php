<?php

namespace App\Console\Commands;

use App\Models\Battery;
use App\Models\CabinetDoors;
use App\Models\Cabinets;
use App\Models\ChargeTasks;
use App\Models\DeviceConfig;
use App\Models\DeviceCostDetail;
use App\Models\DeviceInfo;
use App\Models\HostPortInfos;
use App\Models\ReplaceTasks;
use App\Services\BatteryService;
use App\Services\CabinetService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class StatDeviceCost extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StatDeviceCost {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '充电棚统计每日花费和分成';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('crontab start StatDeviceCost ...');

        $date = $this->option('date');
        $date || $date = Carbon::now()->subDay()->toDateString();

        /*电价谷时段 22:00:00-23:59:59 和 00:00:00～06:00:00  电价峰时段 06:00:01～21:59:59*/
        $begin1 = Carbon::parse($date)->startOfDay()->getTimestamp();
        $end1 = Carbon::parse($date)->endOfDay()->getTimestamp();
        $begin2 = Carbon::parse($date)->setTime(6, 0, 01)->getTimestamp();
        $end2 = Carbon::parse($date)->setTime(21, 59, 59)->getTimestamp();

        DeviceInfo::groupBy('device_no')->select(['device_no'])->chunk(100, function ($datas) use ($date, $begin1, $end1, $begin2, $end2) {
            /** @var DeviceInfo $data */
            foreach ($datas as $data) {

                $deviceNo = $data->device_no;
                $udid = intval($deviceNo);

                $userCost = ChargeTasks::whereDeviceNo($udid)
                    ->whereIn('task_state',ChargeTasks::getFinishStateMap())
                    ->whereBetween('created_at', [Carbon::createFromTimestamp($begin1)->toDateTimeString(), Carbon::createFromTimestamp($end1)->toDateTimeString()])
                    ->sum('actual_cost') ?: 0;

                if($udid == 2100005){
                    dd($userCost, $begin1);
                }

                $begin1Row = HostPortInfos::whereUdid($udid)->whereBetween('create_time', [$begin1, $end1])->orderBy('create_time')->first();
                $end1Row = HostPortInfos::whereUdid($udid)->whereBetween('create_time', [$begin1, $end1])->orderByDesc('create_time')->first();

                $begin2Row = HostPortInfos::whereUdid($udid)->whereBetween('create_time', [$begin2, $end2])->orderBy('create_time')->first();
                $end2Row = HostPortInfos::whereUdid($udid)->whereBetween('create_time', [$begin2, $end2])->orderByDesc('create_time')->first();
                $diff1 = 0;
                $diff2 = 0;

                if ($begin1Row && $end1Row) {
                    //总电量
                    $diff1 = ($end1Row->ammeter_energy - $begin1Row->ammeter_energy) > 0 ? ($end1Row->ammeter_energy - $begin1Row->ammeter_energy) : 0;
                }
                if ($begin2Row && $end2Row) {
                    //高峰端用电量
                    $diff2 = ($end2Row->ammeter_energy - $begin2Row->ammeter_energy) > 0 ? ($end2Row->ammeter_energy - $begin2Row->ammeter_energy) : 0;
                }

                //低锋段电量
                $diffLow = $diff1 - $diff2;

                $deviceCostHigh = $diff2 * DeviceConfig::getUnivalence($deviceNo, 2);//高峰价格
                $deviceCostLow = $diffLow * DeviceConfig::getUnivalence($deviceNo, 1);//低估价格

                $deviceCost = $deviceCostHigh + $deviceCostLow;

                $profit = ($userCost - $deviceCost > 0) ? ($userCost - $deviceCost) : 0;

                $shareMoney = $profit * DeviceConfig::getProportion($deviceNo);

                echo "userCost: $userCost, diff1 : $diff1, diff2 : $diff2, diffLow : $diffLow , deviceCostHigh: $deviceCostHigh, deviceCostLow:$deviceCostLow, deviceCost: $deviceCost"  . "\n";

                DeviceCostDetail::updateOrCreate([
                    'device_no' => $deviceNo,
                    'date' => $date,
                ], [
                    'shared_amount' => $shareMoney,
                    'device_cost_amount' => $deviceCost,
                    'user_cost_amount' => $userCost,
                ]);

            }
        });


        Log::info('crontab end StatDeviceCost ...');
    }

}
