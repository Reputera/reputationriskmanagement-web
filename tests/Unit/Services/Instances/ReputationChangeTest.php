<?php

namespace Tests\Unit\Services\Instances;

use App\Entities\Company;
use App\Entities\Country;
use App\Entities\Instance;
use App\Services\Instance\ReputationChange;
use Carbon\Carbon;

class ReputationChangeTest extends \TestCase
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

    public function testReputationChangeByDateWhenRiskScoresAreMissingForDates()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();
        $startDate = Carbon::create(2016, 05, 01);
        $endDate = Carbon::create(2016, 05, 05);

        // 05-01
        foreach ([31, 47, 59] as $riskScore) {
            $this->createInstanceForCompanyWithScoreAndDate($company, $riskScore, $startDate);
        }

        // 05-03
        $secondDay = clone $startDate;
        $secondDay->addDays(2);
        foreach ([-52, -68, -10] as $riskScore) {
            $this->createInstanceForCompanyWithScoreAndDate($company, $riskScore, $secondDay);
        }

        // 05-05
        $this->createInstanceForCompanyWithScoreAndDate($company, -52, $endDate);

        $this->assertEquals(
            '-19',
            number_format($this->reputationChange->forCompanyBetween($company, $startDate, $endDate), 3)
        );
    }

    public function testReputationChangeByDateAndRegionForDates()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();
        $startDate = Carbon::create(2016, 05, 01);
        $endDate = Carbon::create(2016, 05, 05);
        $countryOne = factory(Country::class)->create();
        $countryTwo = factory(Country::class)->create();

        // 05-01 - Region 1
        foreach ([31, 47, 59] as $riskScore) {
            $instance = $this->createInstanceForCompanyWithScoreAndDate($company, $riskScore, $startDate);
            \DB::table('instance_country')->insert([
                'instance_id' => $instance->id,
                'country_id' => $countryOne->id
            ]);
        }
        // 131 + 147 + 159 = 437 / 3 = 145.6

        // Added for noise, since these are NOT in the region we care about.
        // 05-03 - Region 2
        $secondDay = clone $startDate;
        $secondDay->addDays(2);
        foreach ([-52, -68, -10] as $riskScore) {
            $instance = $this->createInstanceForCompanyWithScoreAndDate($company, $riskScore, $secondDay);
            \DB::table('instance_country')->insert([
                'instance_id' => $instance->id,
                'country_id' => $countryTwo->id
            ]);
        }

//

        // 05-05 - Region 1
        $instance = $this->createInstanceForCompanyWithScoreAndDate($company, -38, $endDate);
        \DB::table('instance_country')->insert([
            'instance_id' => $instance->id,
            'country_id' => $countryOne->id
        ]);

//      62

//        145.6 - 62 = 83
//        145.6 / 83 = 28.5

        $this->assertEquals(
            '-28',
            number_format($this->reputationChange->forCompanyAndRegionBetween(
                $company,
                $countryOne->region,
                $startDate,
                $endDate
            ), 3)
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
