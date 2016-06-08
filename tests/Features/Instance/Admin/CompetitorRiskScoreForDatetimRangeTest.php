<?php

namespace Tests\Features\Instance\Admin;


use App\Entities\Company;
use App\Entities\Instance;
use App\Entities\Vector;
use Carbon\Carbon;

class CompetitorRiskScoreForDatetimRangeTest extends \TestCase
{

    public function testInstancesWithinDateRangeAreFoundCounted()
    {
        /** @var Company $company */
        $company = factory(Company::class)->create();
        $vectorToTest = factory(Vector::class)->create();
        $vectorNotObserved = factory(Vector::class)->create();

        $this->createCompetitorWithInstanceScoreAndVectorForCompany(-20, $company, $vectorToTest);
        $this->createCompetitorWithInstanceScoreAndVectorForCompany(-20, $company, $vectorToTest);
        $this->createCompetitorWithInstanceScoreAndVectorForCompany(41, $company, $vectorToTest);
        $this->createCompetitorWithInstanceScoreAndVectorForCompany(99, $company, $vectorNotObserved);

        $this->beLoggedInAsAdmin();
        $this->apiCall('GET', 'competitors-average-risk-score', [
            'company_name' => $company->name,
            'start_datetime' => Carbon::now()->subDay(2)->toDateTimeString(),
            'end_datetime' => Carbon::now()->toDateTimeString(),
            'vector' => $vectorToTest->id
        ]);
//        dd($this->response);
        $this->assertJsonResponseOkAndFormattedProperly();
        $this->assertJsonResponseHasDataKey('average_competitor_risk_score');

        // -20 - -20 - 41 = .33333
        // We round down, so this should be 0.
        $this->assertEquals(0, json_decode($this->response->getContent(), true)['data']['average_competitor_risk_score']);
    }

    protected function createCompetitorWithInstanceScoreAndVectorForCompany($score, $company, $vector)
    {
        $companyCompetitor = factory(Company::class)->create();
        $company->competitors()->attach($companyCompetitor->id);
        factory(Instance::class)->create([
            'risk_score' => $score,
            'start' => Carbon::now()->subDay(1)->toDateTimeString(),
            'company_id' => $companyCompetitor->id,
            'vector_id' => $vector->id
        ]);
    }
}