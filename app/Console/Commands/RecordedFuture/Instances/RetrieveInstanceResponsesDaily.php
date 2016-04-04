<?php

namespace App\Console\Commands\RecordedFuture\Instances;

use App\Console\Commands\CompaniesFromInput;
use Illuminate\Console\Command;

class RetrieveInstanceResponsesDaily extends Command
{
    use CompaniesFromInput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recorded-future:queue-instances-daily
                            {--days=1 : Number of hours to be processed}
                            {--company= : A specific company to be processed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queues instances for one or all companies for a given number of days.';

    public function handle()
    {
        if ($companies = $this->companiesFromInput()) {
            $days = $this->option('days');
            $companies->each(function ($company) use ($days) {
                $company->queueInstancesDaily($days);
            });
        } else {
            $this->reportNoCompanies();
        }
    }
}
