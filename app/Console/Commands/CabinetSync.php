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

class CabinetSync extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CabinetSync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '换电柜db和redis数据同步';

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
        Log::info('crontab start CabinetSync ...');


        /** @var \Redis $redis */
        $redis = Redis::connection();

        $cabinets = Cabinets::get();
        foreach ($cabinets as $cabinet) {
            $cabinetNo = $cabinet->cabinet_no;
            $key = "cab_door:$cabinetNo";
            $doorsRedis = $redis->sMembers($key);
            $doors = CabinetService::getdoors($cabinet->id);

            $doorNos = [];
            //把db里面的同步到redis
            /** @var CabinetDoors $door */
            foreach ($doors as $door) {
                if (!in_array($door->door_no, $doorsRedis)) {
                    $redis->sAdd($key, $door->door_no);
                }
                $doorNos[] = $door->door_no;
            }

            //把redis里面的和db同步,多的删除
            foreach ($doorsRedis as $doorNo) {
                if (!in_array($doorNo, $doorNos)) {
                    $redis->sRem($key, $doorNo);
                }
            }
            Log::info("cabinetId:{$cabinet->id}, cabinetNo: $cabinetNo smember $key", $redis->sMembers($key));
        }

        $batterys = Battery::get();
        foreach ($batterys as $battery) {
            $batteryId = $battery->battery_id;
            $key = 'bat:' . $batteryId;
            $data = [
                'imei' => $battery->imei,
                'udid' => $battery->udid,
                'voltage' => $battery->battery_level,
            ];
            $redis->hMset($key, $data);
            Log::info("battery: $batteryId hmset : ", $redis->hGetAll($key));
        }


        Log::info('crontab end CabinetSync ...');
    }

}
