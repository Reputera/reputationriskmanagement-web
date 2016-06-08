<?php namespace Tests\Features\Instance\Web;

use App\Entities\Country;
use App\Entities\Instance;
use App\Entities\Role;
use Carbon\Carbon;

class QueryingNonAdminTest extends \TestCase
{
    public function testQueryByAllParametersAndDeletedIsNotObserved()
    {
//        This record is noise, so assert it is not returned in results.
        factory(Instance::class)->create();

        $user = $this->beLoggedInAsUser(['role' => Role::USER]);

        $country = factory(Country::class)->create();
        $returnedInstance = factory(Instance::class)->create(['company_id' => $user->company_id]);
        $returnedInstance->countries()->attach($country);

        $this->apiCall('GET', 'instance', [
            'start_datetime' => $returnedInstance->start->subDay(1),
            'end_datetime' => $returnedInstance->start->addDay(1),
            'vectors_name' => $returnedInstance->vector->name,
            'regions_name' => $country->region->name,
        ]);
        $this->assertJsonResponseOkAndFormattedProperly();
        $results = $this->response->getData(true)['data'];
        $this->assertCount(1, $results);
        $this->assertEquals($returnedInstance->title, array_get($results, '0.title'), 'Assert correct instance returned');
    }

}
