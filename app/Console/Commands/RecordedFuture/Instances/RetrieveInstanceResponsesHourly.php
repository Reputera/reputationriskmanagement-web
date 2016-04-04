<?php

namespace App\Console\Commands\RecordedFuture\Instances;

use App\Console\Commands\CompaniesFromInput;
use Illuminate\Console\Command;

class RetrieveInstanceResponsesHourly extends Command
{
    use CompaniesFromInput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recorded-future:queue-instances-hourly
                            {--hours=1 : Number of hours to be processed}
                            {--company= : A specific company to be processed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queues instances for one or all companies for a given number of hours.';

    public function handle()
    {
        if ($companies = $this->companiesFromInput()) {
            $hours = $this->option('hours');
            $companies->each(function ($company) use ($hours) {
                $company->queueInstancesHourly($hours);
            });
        } else {
            $this->reportNoCompanies();
        }
    }
}
