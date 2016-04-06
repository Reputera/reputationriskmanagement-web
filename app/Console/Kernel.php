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
         $schedule->command('recorded-future:populate-instances-hourly')
                  ->hourly();
    }
}
