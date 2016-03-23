<?php

namespace Tests\Features\Instance;


use App\Entities\Company;
use App\Entities\Instance;
use Carbon\Carbon;

class RiskScoreTest extends \TestCase
{

    public function testQueryByAllParameters()
    {
        factory(Instance::class)->create();
        $company = factory(Company::class)->create();
        factory(Instance::class)->create(['company_id' => $company->id, 'start' => Carbon::now()->subDay(100)]);
        $returnedInstances = factory(Instance::class)->times(5)->create([
            'company_id' => $company->id,
            'start' => Carbon::now(),
            'positive_sentiment' => .5,
            'negative_sentiment' => .1
        ]);

        $this->beLoggedInAsAdmin();
        $this->apiCall('GET', 'riskScore', [
            'start_datetime' => Carbon::now()->subDay(1),
            'end_datetime' => Carbon::now()->addDay(1),
            'company_id' => $company->id,
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();
        $results = $this->response->getData(true)['data'];
        $this->assertEquals($results['risk_score'], .4);
    }

}