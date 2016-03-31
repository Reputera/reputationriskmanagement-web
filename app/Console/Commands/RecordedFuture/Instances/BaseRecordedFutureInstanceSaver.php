<?php

namespace App\Console\Commands\RecordedFuture\Instances;

use App\Entities\Company;
use App\Services\Vendors\RecordedFuture\Api\RecordedFutureApi;
use App\Services\Vendors\RecordedFuture\Repository as RecordedFutureRepository;
use App\Services\Vendors\RecordedFuture\Response;
use Illuminate\Console\Command;

abstract class BaseRecordedFutureInstanceSaver extends Command
{
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

        if ($companies->isEmpty()) {
            $this->info('No company/companies found.');
            return;
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
dd('??');
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
    abstract protected function queryApi(Company $company, array $options = []): Response;

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
