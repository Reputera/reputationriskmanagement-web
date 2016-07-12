<?php

namespace Tests\Features\Instance\API;


use App\Entities\Country;
use App\Entities\Instance;
use Carbon\Carbon;

class SentimentMapTest extends \TestCase
{

    public function testGetRiskScoreMapData()
    {
//        This record is noise, so assert it is not returned in results.
        factory(Instance::class)->create();

        $user = $this->beLoggedInAsUser();

        $country = factory(Country::class)->create();
        $otherCountry = factory(Country::class)->create();
        $returnedInstances = factory(Instance::class)->times(3)->create([
            'company_id' => $user->company_id,
            'risk_score' => 70,
            'start' => Carbon::now()
        ]);

        $otherReturnedInstance = factory(Instance::class)->create([
            'company_id' => $user->company_id,
            'risk_score' => 40,
            'start' => Carbon::now()->subDay(1),
            'vector_id' => $returnedInstances[0]->vector_id
        ]);

        $returnedInstances[0]->countries()->attach($country);
        $returnedInstances[1]->countries()->attach($country);
        $returnedInstances[2]->countries()->attach($otherCountry);
        $otherReturnedInstance->countries()->attach($country);

        $this->apiCall('POST', 'riskScoreMapData', [
            'start_datetime' => $returnedInstances[0]->start->subDay(2),
            'end_datetime' => $returnedInstances[0]->start->addDay(1)
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();

        $results = $this->response->getData(true)['data'];
        $this->assertEquals(10, array_get($results, '0.percent_change'));
        $this->assertEquals('low', array_get($results, '0.risk'));
        $this->assertEquals(3, array_get($results, '0.count'));
        $this->assertEquals($country->region->name, array_get($results, '0.region'));

        $this->assertEquals(2, array_get($results, '0.vectors.0.count'));
    }

}