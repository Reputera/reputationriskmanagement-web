<?php

namespace Tests\Unit\Entities;

use App\Entities\Company;
use App\Entities\Region;
use App\Services\Instance\ReputationChange;
use App\Services\Vendors\RecordedFuture\InstanceApiResponseQueue;
use Carbon\Carbon;

class CompanyTest extends \TestCase
{
    public function testQueueingInstancesByHours()
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

    public function testQueueingInstancesByDays()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();
        /** @var \Mockery\MockInterface $mockInstanceQueuer */
        $mockInstanceQueuer = \Mockery::mock(InstanceApiResponseQueue::class);
        $cadence = 3;

        $mockInstanceQueuer->shouldReceive('processDaily')
            ->once()
            ->with(\Mockery::type(Company::class), $cadence);

        app()->instance(InstanceApiResponseQueue::class, $mockInstanceQueuer);

        $company->queueInstancesDaily($cadence);
    }

    public function testGettingReputationChangeForCompanyBetweenDates()
    {
        $mockedReputationChange = \Mockery::mock(ReputationChange::class);

        $dateOne = Carbon::now();
        $dateTwo = Carbon::now();
        $dateTwo->addDay();
        $mockedReputationChange->shouldReceive('forCompanyBetween')
            ->once()
            ->with(\Mockery::type(Company::class), $dateOne, $dateTwo)
            ->andReturn(0.00);

        app()->instance(ReputationChange::class, $mockedReputationChange);

        /** @var Company $company */
        $company = factory(Company::class)->create();

        $company->reputationChangeBetweenDates($dateOne, $dateTwo);
    }

    public function testGettingReputationChangeForCompanyForRegionBetweenDates()
    {
        $mockedReputationChange = \Mockery::mock(ReputationChange::class);

        $dateOne = Carbon::now();
        $dateTwo = Carbon::now();
        $dateTwo->addDay();
        $mockedReputationChange->shouldReceive('forCompanyAndRegionBetween')
            ->once()
            ->with(\Mockery::type(Company::class), \Mockery::type(Region::class), $dateOne, $dateTwo, null)
            ->andReturn(0.00);

        app()->instance(ReputationChange::class, $mockedReputationChange);

        /** @var Company $company */
        $company = factory(Company::class)->create();
        $region = factory(Region::class)->create();

        $company->reputationChangeForRegionBetweenDates($region, $dateOne, $dateTwo);
    }
}
