<?php

namespace Tests\Unit\Jobs;

use App\Entities\Company;
use App\Jobs\QueueYearlyRecordedFutureInstances;

class QueueYearlyRecordedFutureInstancesTest extends \TestCase
{
    public function testHandle()
    {
        $company = factory(Company::class)->create();
        $job = new QueueYearlyRecordedFutureInstances($company);

        \Artisan::shouldReceive('queue')
            ->once()
            ->with(
                'recorded-future:queue-instances-daily',
                ['--days' => 365, '--company' => $company->name]
            );

        $job->handle();
    }
}
