<?php

namespace App\Console\Commands;

use App\Libs\Daemon;
use App\Models\ChargeTasks;
use App\Services\BoxService;
use App\Services\ChargeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoCloseBox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AutoCloseBox';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动关闭箱子';

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
        //守护进程
        $daemon = new Daemon(true, 'root', storage_path('logs/daemon.log'));
        $daemon->pid_file = 'auto_close_box.pid';
        $daemon->daemonize();
        $daemon->start();

        Log::debug('runing AutoCloseBox ...');
        $timeout = ChargeService::CLOSE_BOX_TIMEOUT;
        $noClose = ChargeTasks::CLOSE_BOX_NOT_SENT;
        $Close = ChargeTasks::CLOSE_BOX_HAS_SENT;

        $num = 0;
        while (true) {
            echo 'used memory :' . memory_get_usage() . ' bytes' . "\n";
            echo ++$num . "\n";
            $result = DB::select("select * from charge_tasks where close_box = $noClose and created_at <= '" . date('Y-m-d H:i:s', strtotime("-$timeout seconds")) . "'");
            if ($result) {
                /** @var ChargeTasks $row */
                foreach ($result as $row) {
                    $id = $row->id;
                    $deviceNo = $row->device_no;
                    $portNo = $row->port_no;
                    Log::debug('close box with task_id: ' . $id);
                    if (BoxService::isOpen($deviceNo, $portNo)){
                        BoxService::closeBox($deviceNo, $portNo);
                    }
                    DB::update("update charge_tasks set close_box = $Close where id=$id");
                }
            }
            usleep(1000000);
        }
    }

}
