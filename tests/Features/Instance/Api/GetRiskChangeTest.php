<?php

namespace Tests\Features\Instance\API;


use App\Entities\Company;
use App\Entities\Instance;
use Carbon\Carbon;

class GetRiskChangeTest extends \TestCase
{

    public function testGetRiskScoreMapData()
    {
//        This record is noise, so assert it is not returned in results.
        factory(Instance::class)->create();

        $user = $this->beLoggedInAsUser();

        factory(Instance::class)->create([
            'company_id' => $user->company_id,
            'start' => Carbon::now(),
            'risk_score' => 100
        ]);

        factory(Instance::class)->create([
            'company_id' => $user->company_id,
            'start' => Carbon::now()->subDay(1),
            'risk_score' => 50
        ]);

//        This record should be outside datetime filter and not be factored into result.
        factory(Instance::class)->create([
            'company_id' => $user->company_id,
            'start' => Carbon::now()->subDay(5),
            'risk_score' => 50
        ]);

        $this->apiCall('GET', 'risk-score-change', [
            'start_datetime' => Carbon::now()->subDay(2),
            'end_datetime' => Carbon::now()->addDay(1)
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();
        $results = $this->response->getData(true)['data'];
        $this->assertEquals(16, array_get($results, 'change_percent'));
    }

    public function testGetIndustryRiskScoreMapData()
    {
//        This record is noise, so assert it is not returned in results.
        factory(Instance::class)->create();

        $user = $this->beLoggedInAsUser();

        $competitor = factory(Company::class)->create();
        $user->company->competitors()->attach($competitor);

        factory(Instance::class)->create([
            'company_id' => $user->company_id,
            'start' => Carbon::now(),
            'risk_score' => 100
        ]);

        factory(Instance::class)->create([
            'company_id' => $competitor->id,
            'start' => Carbon::now()->subDay(1),
            'risk_score' => 50
        ]);

//        This record should be outside datetime filter and not be factored into result.
        factory(Instance::class)->create([
            'company_id' => $user->company_id,
            'start' => Carbon::now()->subDay(5),
            'risk_score' => 50
        ]);

        $this->apiCall('GET', 'industry-risk-score-change', [
            'start_datetime' => Carbon::now()->subDay(2),
            'end_datetime' => Carbon::now()->addDay(1)
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();
        $results = $this->response->getData(true)['data'];
        $this->assertEquals(16, array_get($results, 'change_percent'));
    }

}