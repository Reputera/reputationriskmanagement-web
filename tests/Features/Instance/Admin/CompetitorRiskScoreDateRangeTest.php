<?php

namespace Tests\Features\Instance\Admin;

use App\Entities\Company;
use App\Entities\Instance;
use App\Http\Queries\Instance as InstanceQuery;
use Carbon\Carbon;

class CompetitorRiskScoreDateRangeTest extends \TestCase
{
    public function testAverageCompetitorRiskScoreForSevenDaysBackFromToday()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();

        $now = Carbon::now();
        // Counted
        $this->createCompetitorWithInstanceScoreAndDateTimeForCompany(5, $company, $now->toDateTimeString());
        // Not counted, in the future.
        $this->createCompetitorWithInstanceScoreAndDateTimeForCompany(25, $company, $now->addDay(1)->toDateTimeString());
        //Not counted, too far in the past.
        $this->createCompetitorWithInstanceScoreAndDateTimeForCompany(31, $company, $now->subDay(9)->toDateTimeString());

        $this->beLoggedInAsAdmin();
        $this->apiCall('GET', 'competitors-average-risk-score', [
            'company_name' => $company->name,
            'lastDays' => 7
        ]);

        $this->assertJsonResponseOkAndFormattedProperly();
        $this->assertJsonResponseHasDataKey('average_competitor_risk_score');

        // There was only one instance for the $now var - so, (5 / 1) = 5 and with rounding, it's still 5.
        $this->assertEquals(5, json_decode($this->response->getContent(), true)['data']['average_competitor_risk_score']);
    }

    public function testAverageCompetitorRiskScoreForThirtyDaysBackFromToday()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();

        $now = Carbon::now();
        $this->createCompetitorWithInstanceScoreAndDateTimeForCompany(6, $company, $now->toDateTimeString());
        $this->createCompetitorWithInstanceScoreAndDateTimeForCompany(25, $company, $now->subDays(30)->toDateTimeString());
        $this->createCompetitorWithInstanceScoreAndDateTimeForCompany(31, $company, $now->subYear(1)->toDateTimeString());

        $this->beLoggedInAsAdmin();
        $this->apiCall('GET', 'competitors-average-risk-score', [
            'company_name' => $company->name,
            'lastDays' => 30
        ]);

        $this->assertJsonResponseOkAndFormattedProperly();
        $this->assertJsonResponseHasDataKey('average_competitor_risk_score');

        // There was only one instance for the $now var - so, ((6 + 25) / 2) = 15.5. Rounding down, that's 16
        $this->assertEquals(16, json_decode($this->response->getContent(), true)['data']['average_competitor_risk_score']);
    }

    protected function createCompetitorWithInstanceScoreAndDateTimeForCompany($score, $company, $start)
    {
        $companyCompetitor = factory(Company::class)->create();
        $company->competitors()->attach($companyCompetitor->id);
        factory(Instance::class)->create([
            'risk_score' => $score,
            'company_id' => $companyCompetitor->id,
            'start' => $start
        ]);
    }
}
