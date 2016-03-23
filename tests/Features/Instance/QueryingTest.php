<?php namespace Tests\Features\Instance;


use App\Entities\Country;
use App\Entities\Instance;
use Carbon\Carbon;

class QueryingTest extends \TestCase
{

    public function testQueryByAllParameters()
    {
//        This record is noise, so assert it is not returned in results.
        factory(Instance::class)->create();

        $country = factory(Country::class)->create();
        $returnedInstance = factory(Instance::class)->create();
        $returnedInstance->countries()->attach($country);

        $this->beLoggedInAsAdmin();
        $this->apiCall('GET', 'instance', [
            'start_datetime' => $returnedInstance->start->subDay(1),
            'end_datetime' => $returnedInstance->start->addDay(1),
            'vectors_name' => $returnedInstance->vector->name,
            'regions_name' => $country->region->name,
            'companies_name' => $returnedInstance->company->name,
            'risk_score' => -100
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();
        $results = $this->response->getData(true)['data']['instances']['data'];
        $this->assertCount(1, $results);
        $this->assertEquals($returnedInstance->id, array_get($results, '0.id'), 'Assert correct instance returned');
    }

}