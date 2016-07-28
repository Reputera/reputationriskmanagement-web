<?php

namespace Tests\Features\Instance\Admin;

use App\Entities\Company;

class CompetitorRiskScoreNoDataTest extends \TestCase
{
    public function testAverageCompetitorRiskScoreForSevenDaysBackFromToday()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();

        $this->beLoggedInAsAdmin();
        $this->apiCall('GET', 'competitors-average-risk-score', [
            'company_name' => $company->name,
            'lastDays' => 7
        ]);

        $this->assertJsonResponseOkAndFormattedProperly();
        $this->assertJsonResponseHasDataKey('average_competitor_risk_score');

        $this->assertEquals('N/A', json_decode($this->response->getContent(), true)['data']['company_risk_score']);
        $this->assertEquals('N/A', json_decode($this->response->getContent(), true)['data']['average_competitor_risk_score']);

    }
}
