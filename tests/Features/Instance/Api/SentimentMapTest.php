<?php

namespace Tests\Features\Instance\API;


use App\Entities\Country;
use App\Entities\Instance;
use Carbon\Carbon;

class SentimentMapTest extends \TestCase
{

    public function testQueryByAllParameters()
    {
//        This record is noise, so assert it is not returned in results.
        factory(Instance::class)->create();

        $user = $this->beLoggedInAsUser();

        $country = factory(Country::class)->create();
        $returnedInstances = factory(Instance::class)->times(2)->create([
            'company_id' => $user->company_id,
            'start' => Carbon::now()
        ]);

        factory(Instance::class)->create([
            'company_id' => $user->company_id,
            'start' => Carbon::now(),
            'vector_id' => $returnedInstances[0]->vector_id
        ]);

        $returnedInstances[0]->countries()->attach($country);
        $returnedInstances[1]->countries()->attach($country);

        $this->apiCall('POST', 'setimentMap', [
            'start_datetime' => $returnedInstances[0]->start->subDay(1),
            'end_datetime' => $returnedInstances[0]->start->addDay(1)
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();

        $results = $this->response->getData(true)['data'];
        $this->assertEquals(2, array_get($results, '0.count'));
        $this->assertEquals($country->region->name, array_get($results, '0.region'));
    }

}