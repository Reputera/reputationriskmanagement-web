<?php

namespace App\Console\Commands\RecordedFuture\Instances;

use App\Entities\Company;
use App\Services\Vendors\RecordedFuture\Response;

class PopulateCompanyHourly extends BaseRecordedFutureInstanceSaver
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recorded-future:populate-instances-hourly
                            {--hours=1 : Number of hours to be processed}
                            {--company= : A specific company to be processed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates companies with Recorded Future instances for a given number of hours.';

    /**
     * {@inheritDoc}
     */
    protected function queryApi(Company $company, array $options = []): Response
    {
        return $this->recordedFutureApi
            ->queryInstancesForEntityHourly($company->entity_id, $this->option('hours'), $options);
    }
}
