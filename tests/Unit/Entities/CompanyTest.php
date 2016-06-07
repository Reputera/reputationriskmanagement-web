<?php

namespace Tests\Unit\Entities;

use App\Entities\Company;
use App\Entities\Instance;
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

    public function testReputationChangeByDateWhenRiskScoresAreMissingForDates()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();
        $startDate = Carbon::create(2016, 05, 01);
        $endDate = Carbon::create(2016, 05, 05);

        // 05-01
        factory(Instance::class)->create(['company_id' => $company->id, 'risk_score' => 31, 'start' => $startDate->toDateTimeString()]);
        factory(Instance::class)->create(['company_id' => $company->id, 'risk_score' => 47, 'start' => $startDate->toDateTimeString()]);
        factory(Instance::class)->create(['company_id' => $company->id, 'risk_score' => 59, 'start' => $startDate->toDateTimeString()]);
        // 45.6667 - 43.3333 = 89.000 / 45.6667 = 1.9489 = -194.89%

        // 05-03
        $secondDay = clone $startDate;
        $secondDay->addDays(2);
        factory(Instance::class)->create(['company_id' => $company->id, 'risk_score' => -52, 'start' => $secondDay->toDateTimeString()]);
        factory(Instance::class)->create(['company_id' => $company->id, 'risk_score' => -68, 'start' => $secondDay->toDateTimeString()]);
        factory(Instance::class)->create(['company_id' => $company->id, 'risk_score' => -10, 'start' => $secondDay->toDateTimeString()]);
        // -43.3333 - -52.0000 = -8.666 / -43.3333 = .20 = -20%

        // 05-05
        // These shouldn't count since we can't compare to a "next day"
        factory(Instance::class)->create(['company_id' => $company->id, 'risk_score' => -52, 'start' => $endDate->toDateTimeString()]);

        // -194.89 + -20 = 214 / 2 = 107.445
        $this->assertEquals('-107.445', number_format($company->reputationChangeByDate($startDate, $endDate), 3));
    }
}
