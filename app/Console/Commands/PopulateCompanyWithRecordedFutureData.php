<?php

namespace App\Console\Commands;

use App\Entities\Company;
use App\Services\Vendors\RecordedFuture\RecordedFutureApi;
use App\Services\Vendors\RecordedFuture\Repository as RecordedFutureRepository;
use Illuminate\Console\Command;

class PopulateCompanyWithRecordedFutureData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recorded-future:populate-instances
                            {--days=1 : Number of days to be processed}
                            {--company= : A specific company to be processed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates companies with Recorded Future instances for a given number of days.';

    /**
     * The Recorded Future API.
     *
     * @var RecordedFutureApi
     */
    protected $recordedFutureApi;

    /**
     * The Recorded Future repository for saving things from the API calls.
     *
     * @var RecordedFutureRepository
     */
    protected $recordedFutureRepo;

    public function __construct(RecordedFutureApi $recordedFutureApi, RecordedFutureRepository $repo)
    {
        parent::__construct();
        $this->recordedFutureApi = $recordedFutureApi;
        $this->recordedFutureRepo = $repo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Company $company */
        if ($companyName = $this->option('company')) {
            $companies = Company::whereName($companyName)->get();
        } else {
            $companies = Company::all();
        }

        if (!$companies) {
            exit;
        }

        $this->recordedFutureApi->setLimit(500);

        foreach ($companies as $company) {
            $this->saveCompanyResults($company);
        }
    }

    /**
     * Does the work of saving a given companies Recorded Future instances.
     *
     * @param Company $company
     */
    protected function saveCompanyResults(Company $company)
    {
        $done = false;
        $safetyValve = 0;
        $startPage = 0;
        $numberOfDays = $this->option('days');

        while (!$done && $safetyValve <= 10000000) {
            $apiResponse = $this->recordedFutureApi
                ->setPageStart($startPage)
                ->queryInstancesForEntity($company->entity_id, $numberOfDays);

            if (!$nextPageStart = $apiResponse->getNextPageStart()) {
                $done = true;
            } else {
                $startPage = $nextPageStart;
            }

            foreach ($apiResponse->getInstances() as $instance) {
                $this->recordedFutureRepo->saveInstanceForCompany($instance, $company);
            }
        }
    }
}
