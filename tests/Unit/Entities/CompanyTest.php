<?php

namespace Tests\Unit\Entities;

use App\Entities\Company;
use App\Services\Vendors\RecordedFuture\InstanceApiResponseQueue;

class CompanyTest extends \TestCase
{
    public function testQueueingInstancesByHour()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();
        /** @var \Mockery\MockInterface $mockInstanceQueuer */
        $mockInstanceQueuer = \Mockery::mock(InstanceApiResponseQueue::class);
        $cadence = 3;

        $mockInstanceQueuer->shouldReceive('processHourly')
            ->once()
            ->with(\Mockery::type(Company::class), $cadence);

        app()->instance(InstanceApiResponseQueue::class, $mockInstanceQueuer);

        $company->queueInstancesHourly($cadence);
    }
}
