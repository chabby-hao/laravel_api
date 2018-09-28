<?php

namespace App\Console\Commands;

use App\Models\ChargeTasks;
use App\Services\ChargeService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Foreach_;

class AutoFinishCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AutoFinishCharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '充电时间到自动下发结束命令';

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
        Log::info('crontab start AutoFinishCharge ...');
        $state = ChargeTasks::TASK_STATE_CHARGING;
        $datePre = date('Y-m-d H:i:s', strtotime('-20 days'));
        $result = DB::select("select * from charge_tasks where task_state = $state and created_at >= '$datePre'");
        $dateNow = date('Y-m-d H:i:s');
        if($result){
            /** @var ChargeTasks $row */
            foreach ($result as $row){
                $expectEndAt = $row->expect_end_at;
                if($expectEndAt && $dateNow >= $expectEndAt){
                    Log::info('crontab autoFinishCharge ' . json_encode($row));
                    //充电时间已满，可以终止充电
                    ChargeService::endChargeByTimeOver($row->device_no,$row->port_no);
                }

                //充10小时自动结束
                if(time() - Carbon::parse($row->begin_at)->getTimestamp() > 3600 * 10){

                    Log::info('crontab autoFinishCharge for 10 hours ' . json_encode($row));
                    ChargeService::endChargeByTimeOver($row->device_no,$row->port_no);
                }

            }
        }
        Log::info('crontab end AutoFinishCharge ...');

    }

}
