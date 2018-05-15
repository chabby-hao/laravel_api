<?php

namespace App\Console;

use App\Console\Commands\AutoCloseBox;
use App\Console\Commands\AutoFinishCharge;
use App\Console\Commands\CabinetSync;
use App\Console\Commands\ReplaceTimeout;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        AutoFinishCharge::class,
        AutoCloseBox::class,
        ReplaceTimeout::class,
        CabinetSync::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command(AutoFinishCharge::class)->everyMinute();
        $schedule->command(ReplaceTimeout::class)->everyMinute();
        $schedule->command(CabinetSync::class)->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
