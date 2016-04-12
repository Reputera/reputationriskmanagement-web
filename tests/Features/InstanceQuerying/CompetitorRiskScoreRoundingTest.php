<?php

namespace Tests\Features\Instance;

use App\Entities\Company;
use App\Entities\Instance;

class CompetitorRiskScoreRoundingTest extends \TestCase
{
    public function testAverageCompetitorRiskScoreRoundsUp()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();

        $this->createCompetitorWithInstanceScoreForCompany(-20, $company);
        $this->createCompetitorWithInstanceScoreForCompany(-19, $company);
        $this->createCompetitorWithInstanceScoreForCompany(31, $company);

        $this->beLoggedInAsAdmin();
        $this->apiCall('GET', 'competitors-average-risk-score', [
            'company_name' => $company->name,
        ]);

        $this->assertJsonResponseOkAndFormattedProperly();
        $this->assertJsonResponseHasDataKey('average_competitor_risk_score');

        // (31 - 19 - 20) = -8
        // -8 / 3 = -2.6 (Since we are rounding, this should round up to -3)
        $this->assertEquals(-3, json_decode($this->response->getContent(), true)['data']['average_competitor_risk_score']);
    }

    public function testAverageCompetitorRiskScoreRoundsDown()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();

        $this->createCompetitorWithInstanceScoreForCompany(-19, $company);
        $this->createCompetitorWithInstanceScoreForCompany(-19, $company);
        $this->createCompetitorWithInstanceScoreForCompany(31, $company);

        $this->beLoggedInAsAdmin();
        $this->apiCall('GET', 'competitors-average-risk-score', [
            'company_name' => $company->name,
        ]);

        $this->assertJsonResponseOkAndFormattedProperly();
        $this->assertJsonResponseHasDataKey('average_competitor_risk_score');

        // (31 - 19 - 19) = -7
        // -7 / 3 = -2.33 (Since we are rounding, this should round down to -2)
        $this->assertEquals(-2, json_decode($this->response->getContent(), true)['data']['average_competitor_risk_score']);
    }

    protected function createCompetitorWithInstanceScoreForCompany($score, Company $company)
    {
        $companyCompetitor = factory(Company::class)->create();
        $company->competitors()->attach($companyCompetitor->id);
        factory(Instance::class)->create(['risk_score' => $score, 'company_id' => $companyCompetitor->id]);
    }
}
