<?php

namespace App\Jobs;

use App\Entities\Company;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueueYearlyRecordedFutureInstances extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /** @var Company */
    protected $company;

    /**
     * Create a new job instance.
     *
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        \Artisan::queue('recorded-future:queue-instances-daily', [
            '--days' => 365, '--company' => $this->company->name,
        ]);
    }
}
