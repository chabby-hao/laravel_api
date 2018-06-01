<?php

namespace App\Console\Commands;

use App\Models\Battery;
use App\Models\CabinetDoors;
use App\Models\Cabinets;
use App\Models\ReplaceTasks;
use App\Services\BatteryService;
use App\Services\CabinetService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class BatteryControl extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BatteryControl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '电池输出状态维护';

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
        //
        Log::info('crontab start BatteryControl ...');

        /** @var \Redis $redis */
        $redis = Redis::connection();

        $batterys = Battery::get();
        foreach ($batterys as $battery) {
            $batteryId = $battery->battery_id;
            $key = 'bat:' . $batteryId;
            $batteryInfo = BatteryService::getBatteryInfo($batteryId);
            $zhangfei = BatteryService::getZhangfeiByBatteryId($batteryId);
            if(array_key_exists('batteryState', $batteryInfo)){
                $state = $batteryInfo['batteryState'];
                if(in_array($state, [BatteryService::BATTERY_STATE_UNUSEFUL]) && $zhangfei['abkBatteryLockStatus'] == 0){
                    //不可用，实际状态开了,要关闭输出
                    BatteryService::closeBatteryOutput($battery->udid);
                }elseif(in_array($state, [BatteryService::BATTERY_STATE_USEFUL, BatteryService::BATTERY_STATE_USING,BatteryService::BATTERY_STATE_OPS]) &&
                    $zhangfei['abkBatteryLockStatus'] == 1
                ){
                    //可用，使用中，维护中，实际状态没开,要打开输出
                    BatteryService::openBatteryOutput($battery->udid);
                }
            }
            Log::info("battery: $batteryId hgetall : ", $redis->hGetAll($key));
        }


        Log::info('crontab end BatteryControl ...');
    }

}
