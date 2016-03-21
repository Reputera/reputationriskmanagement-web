<?php

namespace App\Console\Commands;

use App\Entities\Company;
use App\Services\Vendors\RecordedFuture\RecordedFutureApi;
use App\Services\Vendors\RecordedFuture\Repository as RecordedFutureRepository;
use App\Services\Vendors\RecordedFuture\Response;
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
        $apiResponse = $this->queryApi($company);

        $safetyValve = $apiResponse->countOfReferences();
        while ($apiResponse->getInstances() && $safetyValve) {
            $this->saveInstances($company, $apiResponse->getInstances());

            if (!$this->hasMoreResults($apiResponse)) {
                break;
            }

            $apiResponse = $this->queryApi($company, ['page_start' => $apiResponse->getNextPageStart()]);
            $safetyValve--;
        }
    }

    /**
     * Queries the API with the given company and options.
     *
     * @param Company $company
     * @param array $options
     * @return Response
     */
    protected function queryApi(Company $company, array $options = []): Response
    {
        $numberOfDays = $this->option('days');

        return $this->recordedFutureApi
            ->queryInstancesForEntity($company->entity_id, $numberOfDays, $options);
    }

    /**
     * Save all instances for a company
     *
     * @param Company $company
     * @param array $instances
     * @return void
     */
    protected function saveInstances(Company $company, array $instances)
    {
        foreach ($instances as $instance) {
            $this->recordedFutureRepo->saveInstanceForCompany($instance, $company);
        }
    }

    /**
     * Checks of a response has more results to process.
     *
     * @param Response $apiResponse
     * @return bool
     */
    protected function hasMoreResults(Response $apiResponse): bool
    {
        if (!$apiResponse->hasMorePages() || !$apiResponse->countOfReferences()) {
            // No more records, so no need to make more queries.
            return false;
        }
        return true;
    }
}
