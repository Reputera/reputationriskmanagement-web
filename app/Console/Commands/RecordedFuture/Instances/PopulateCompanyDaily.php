<?php

namespace App\Console\Commands\RecordedFuture\Instances;

use App\Entities\Company;
use App\Services\Vendors\RecordedFuture\Response;

class PopulateCompanyDaily extends BaseRecordedFutureInstanceSaver
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recorded-future:populate-instances-daily
                            {--days=1 : Number of days to be processed}
                            {--company= : A specific company to be processed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates companies with Recorded Future instances for a given number of days.';

    /**
     * {@inheritDoc}
     */
    protected function queryApi(Company $company, array $options = []): Response
    {
        return $this->recordedFutureApi
            ->queryInstancesForEntityDaily($company->entity_id, $this->option('days'), $options);
    }
}
