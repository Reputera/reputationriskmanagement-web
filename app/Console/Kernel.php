<?php

namespace App\Console;

use App\Console\Commands\RecordedFuture\Instances\RetrieveInstanceResponsesDaily;
use App\Console\Commands\RecordedFuture\Instances\RetrieveInstanceResponsesHourly;
use App\Console\Commands\RecordedFuture\Instances\QueueProcessor;
use App\Console\Commands\SystemCleanup\Instance as SystemCleanupInstance;
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
        QueueProcessor::class,
        SystemCleanupInstance::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('system-cleanup:instances')
            ->dailyAt('00:00')
            ->after(function () {
                \Log::info('Command system-cleanup:instances ran');
            });

        $schedule->command('recorded-future:queue-instances-hourly', ['--hours' => 1])
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
