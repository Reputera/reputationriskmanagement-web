<?php

namespace Tests\Unit\Services\Instances;

use App\Entities\Company;
use App\Entities\Country;
use App\Entities\Instance;
use App\Services\Instance\ReputationChange;
use Carbon\Carbon;

class CompetitorReputationChangeTest extends \TestCase
{
    /**
     * @var ReputationChange
     */
    protected $reputationChange;

    public function setUp()
    {
        parent::setUp();
        $this->reputationChange = new ReputationChange;
    }

    public function testReputationChangeByDateForDates()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();
        $startDate = Carbon::create(2016, 05, 01);
        $endDate = Carbon::create(2016, 05, 05);

        $secondDay = clone $startDate;
        $secondDay->addDays(1);

        $outsideDateRange = clone $endDate;
        $outsideDateRange->addDay(1);

        $companyCompetitor = factory(Company::class)->create();
        $company->competitors()->attach($companyCompetitor->id);

//        Day 1 total = 10 + 100 + 20 + 100 = 230
        $this->createInstanceForCompanyWithScoreAndDate($company, 10, $startDate);
        $this->createInstanceForCompanyWithScoreAndDate($companyCompetitor, 20, $startDate);

//        Day 2 total = 20 + 100 + 40 + 100 = 260
        $this->createInstanceForCompanyWithScoreAndDate($company, 20, $secondDay);
        $this->createInstanceForCompanyWithScoreAndDate($companyCompetitor, 40, $secondDay);

        // This shouldn't count since we can't compare to a "next day"
        $this->createInstanceForCompanyWithScoreAndDate($company, -38, $outsideDateRange);


//        Day  230 -  260 / 230 = .13 * 50 = 6.5
        $this->assertEquals(
            '6',
            number_format($this->reputationChange->forCompetitorsBetween(
                $company,
                $startDate,
                $endDate
            ), 3)
        );
    }

    public function testReputationChangeByDateForDatesWithZeroScoreInstance()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();
        $startDate = Carbon::create(2016, 05, 01);
        $endDate = Carbon::create(2016, 05, 05);

        $secondDay = clone $startDate;
        $secondDay->addDays(1);

        $companyCompetitor = factory(Company::class)->create();
        $company->competitors()->attach($companyCompetitor->id);

//        Day 1 total = 30
        $this->createInstanceForCompanyWithScoreAndDate($company, 0, $startDate);

//        Day 2 total = 60
        $this->createInstanceForCompanyWithScoreAndDate($company, 50, $secondDay);

//        Day 2 (60) -  Day 1 (30) = 30 / 30 = 1 * 100
        $this->assertEquals(
            '25',
            number_format($this->reputationChange->forCompetitorsBetween(
                $company,
                $startDate,
                $endDate
            ), 3)
        );
    }

    public function testReputationChangeWithNoInstancesReturned()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();
        $startDate = Carbon::create(2016, 05, 01);
        $endDate = Carbon::create(2016, 05, 05);

        $secondDay = clone $startDate;
        $secondDay->addDays(1);

        $companyCompetitor = factory(Company::class)->create();
        $company->competitors()->attach($companyCompetitor->id);

        $this->assertEquals(
            'N/A',
            $this->reputationChange->forCompetitorsBetween(
                $company,
                $startDate,
                $endDate
            )
        );
    }

    /**
     * @param Company $company
     * @param $riskScore
     * @param Carbon $date
     * @return Instance
     */
    protected function createInstanceForCompanyWithScoreAndDate(Company $company, $riskScore, Carbon $date)
    {
        return factory(Instance::class)->create([
            'company_id' => $company->id,
            'risk_score' => $riskScore,
            'start' => $date->toDateTimeString()
        ]);
    }
}
