<?php namespace Tests\Features\Instance\API;

use App\Entities\Country;
use App\Entities\Instance;
use Carbon\Carbon;

class QueryingTest extends \TestCase
{
    public function testQueryByAllParameters()
    {
//        This record is noise, so assert it is not returned in results.
        factory(Instance::class)->create();

        $user = $this->beLoggedInAsUser();

        $country = factory(Country::class)->create();
        $returnedInstance = factory(Instance::class)->create(['company_id' => $user->company_id]);
        $returnedInstance->countries()->attach($country);

        $this->apiCall('GET', 'myInstances', [
            'start_datetime' => $returnedInstance->start->subDay(1),
            'end_datetime' => $returnedInstance->start->addDay(1),
            'vectors_name' => $returnedInstance->vector->name,
            'regions_name' => $country->region->name,
        ]);

        $this->assertJsonResponseOkAndFormattedProperly();

        $results = $this->response->getData(true)['data']['instances']['data'];
        $this->assertCount(1, $results);
        $this->assertEquals($returnedInstance->title, array_get($results, '0.title'), 'Assert correct instance returned');
    }

    public function testQueryTextSearch()
    {
//        This record is noise, so assert it is not returned in results.
        factory(Instance::class)->create();

        $returnedInstance = factory(Instance::class)->create(['fragment' => 'fragment text']);

        $this->beLoggedInAsAdmin();
        $this->apiCall('GET', 'instance', [
            'companies_name' => $returnedInstance->company->name,
            'fragment' => 'fragment text'
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();
        $results = $this->response->getData(true)['data']['instances']['data'];
        $this->assertCount(1, $results);
        $this->assertEquals($returnedInstance->title, array_get($results, '0.title'), 'Assert correct instance returned');
    }
}
