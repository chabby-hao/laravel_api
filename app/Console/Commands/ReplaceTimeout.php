<?php

namespace App\Console\Commands;

use App\Models\ChargeTasks;
use App\Models\ReplaceTasks;
use App\Services\ChargeService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Foreach_;

class ReplaceTimeout extends Command
{

    const TIME_OUT = 60;//秒

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ReplaceTimeOut';

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
        Log::info('crontab start ReplaceTimeout ...');

        $createdIn = [
            Carbon::now()->subDay()->toDateTimeString(),
            Carbon::now()->toDateTimeString(),
        ];

        $timeout = Carbon::now()->subSeconds(self::TIME_OUT)->toDateTimeString();

        $tasks = ReplaceTasks::whereBetween('created_at',$createdIn)->whereState(ReplaceTasks::TASK_STATE_INIT)->get();
        if($tasks){
            /** @var ReplaceTasks $task */
            foreach ($tasks as $task){
                if($task->created_at < $timeout){
                    $task->state = ReplaceTasks::TASK_STATE_TIMEOUT;//超时
                    Log::debug('replace timeout : ', $task->toArray());
                }
            }
        }

        Log::info('crontab end ReplaceTimeout ...');
    }

}
