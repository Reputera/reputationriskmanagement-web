<?php namespace Tests\Features\Instance\Web;

use App\Entities\Country;
use App\Entities\Instance;
use Carbon\Carbon;

class QueryingTest extends \TestCase
{
    public function testQueryByAllParametersAndDeletedIsNotObserved()
    {
//        This record is noise, so assert it is not returned in results.
        factory(Instance::class)->create();

        $country = factory(Country::class)->create();
        $returnedInstance = factory(Instance::class)->create();
        $returnedInstance->countries()->attach($country);

        factory(Instance::class)->create(['risk_score' => 0])->countries()->attach($country);

        $deletedInstance = factory(Instance::class)->create([
            'start' => $returnedInstance->start,
            'vector_id' => $returnedInstance->vector->id,
            'company_id' => $returnedInstance->company->id,
        ]);
        $deletedInstance->countries()->attach($country);
        // Delete it to show it won't be pulled.
        $deletedInstance->delete();

        $this->beLoggedInAsAdmin();
        $this->apiCall('GET', 'instance', [
            'start_datetime' => $returnedInstance->start->subDay(1),
            'end_datetime' => $returnedInstance->start->addDay(1),
            'vectors_name' => $returnedInstance->vector->name,
            'regions_name' => $country->region->name,
            'companies_name' => $returnedInstance->company->name,
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();
        $results = $this->response->getData(true)['data'];
        $this->assertCount(1, $results);
        $this->assertEquals($returnedInstance->title, array_get($results, '0.title'), 'Assert correct instance returned');
    }

    public function testQueryByAllParametersAndDeletedIsObserved()
    {
//        This record is noise, so assert it is not returned in results.
        factory(Instance::class)->create();

        $country = factory(Country::class)->create();
        $returnedInstance = factory(Instance::class)->create();
        $returnedInstance->countries()->attach($country);

        $deletedInstance = factory(Instance::class)->create([
            'start' => $returnedInstance->start,
            'vector_id' => $returnedInstance->vector->id,
            'company_id' => $returnedInstance->company->id,
        ]);
        $deletedInstance->countries()->attach($country);
        // Deleted, but should be pulled
        $deletedInstance->delete();

        $this->beLoggedInAsAdmin();
        $this->apiCall('GET', 'instance', [
            'showDeleted' => true,
            'start_datetime' => $returnedInstance->start->subDay(1),
            'end_datetime' => $returnedInstance->start->addDay(1),
            'vectors_name' => $returnedInstance->vector->name,
            'regions_name' => $country->region->name,
            'companies_name' => $returnedInstance->company->name,
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();
        $results = $this->response->getData(true)['data'];
        $this->assertCount(2, $results);
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
        $results = $this->response->getData(true)['data'];
        $this->assertCount(1, $results);
        $this->assertEquals($returnedInstance->title, array_get($results, '0.title'), 'Assert correct instance returned');
    }
}
