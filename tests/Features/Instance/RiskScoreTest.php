<?php

namespace Tests\Features\Instance;


use App\Entities\Company;
use App\Entities\Instance;
use Carbon\Carbon;

class RiskScoreTest extends \TestCase
{

    public function tesstQueryByAllParameters()
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
            'companies_name' => $company->name,
            'start_datetime' => Carbon::now()->subDay(1),
            'end_datetime' => Carbon::now()->addDay(1),
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();
        $results = $this->response->getData(true)['data'];
        $this->assertEquals(40, $results['risk_score']);
    }

    public function testQueryForMultipleCompanies()
    {
        factory(Instance::class)->create();
        $company = factory(Company::class)->create();
        factory(Instance::class)->create(['company_id' => $company->id, 'start' => Carbon::now()->subDay(100)]);
        $returnedInstance = factory(Instance::class)->create(['sentiment' => .2]);
        $returnedInstance2 = factory(Instance::class)->create(['sentiment' => .5]);
        $this->beLoggedInAsAdmin();
        $this->apiCall('GET', 'riskScore', [
            'companies_name' => $returnedInstance->company->name . ',' . $returnedInstance2->company->name,
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();
        $results = $this->response->getData(true)['data'];
        $this->assertEquals(35, $results['risk_score']);
    }

}