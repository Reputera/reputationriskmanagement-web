<?php

namespace App\Console;

use App\Console\Commands\RecordedFuture\Instances\RetrieveInstanceResponsesDaily;
use App\Console\Commands\RecordedFuture\Instances\RetrieveInstanceResponsesHourly;
use App\Console\Commands\RecordedFuture\Instances\QueueProcessor;
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
        RetrieveInstanceResponsesDaily::class,
        RetrieveInstanceResponsesHourly::class,
        QueueProcessor::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('recorded-future:queue-instances-hourly')
            ->hourly()
            ->after(function () {
                \Log::info('Command recorded-future:queue-instances-hourly ran');
            });

        $schedule->command('recorded-future:process-instance-queue')
            ->everyThirtyMinutes()
            ->after(function () {
                \Log::info('Command recorded-future:process-instance-queue ran');
            });
    }
}
