<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture;

use App\Entities\Company;
use App\Entities\Country;
use App\Entities\Region;
use App\Entities\Vector;
use App\Entities\VectorEventType;
use App\Services\Vendors\RecordedFuture\Instance;
use App\Services\Vendors\RecordedFuture\Repository;
use Carbon\Carbon;
use Tests\StubData\RecordedFuture\SingleInstance;

class RepositoryTest extends \TestCase
{
    /** @var Repository */
    protected $repo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = new Repository();
    }

    public function test_saving_an_instance_when_country_cannot_be_found()
    {
        $eventVectors = VectorEventType::keys();
        $randomVector = $eventVectors[array_rand($eventVectors)];

        $vector = factory(Vector::class)->create(['name' => ucfirst(strtolower($randomVector))]);

        $vectorEventTypes = VectorEventType::valueByStringKey($randomVector);
        $eventType = $vectorEventTypes[array_rand($vectorEventTypes)];

        $singleInstance = SingleInstance::get(['event_type' => $eventType]);
        $instance = new Instance($singleInstance['instances'][0]);
        $company = factory(Company::class)->create();

        $this->repo->saveInstanceForCompany($instance, $company);

        $this->assertInstanceInDb($company, $singleInstance['instances'][0], $vector);
    }

    public function test_saving_an_instance_when_vector_cannot_be_found()
    {
        $singleInstance = SingleInstance::get(['event_type' => 'SomeRandomEventNotInTheSystem']);
        $instance = new Instance($singleInstance['instances'][0]);
        $company = factory(Company::class)->create();

        $this->repo->saveInstanceForCompany($instance, $company);

        $this->assertInstanceInDb($company, $singleInstance['instances'][0]);
    }

    public function test_saving_an_instance_when_everything_is_found()
    {
        $singleInstance = SingleInstance::get()['instances'][0];

        $vector = factory(Vector::class)->create(['name' => VectorEventType::getVectorNameFromEventType($singleInstance['type'])]);

        $country = factory(Country::class)->create(['name' => $singleInstance['document']['sourceId']['country']]);

        $instance = new Instance($singleInstance);
        $company = factory(Company::class)->create();

        $this->repo->saveInstanceForCompany($instance, $company);

        $this->assertInstanceInDb($company, $singleInstance, $vector, $country, $country->region);
    }

    protected function assertInstanceInDb(Company $company, array $instance, Vector $vector = null, Country $country = null, Region $region = null)
    {
        $this->seeInDatabase('instances', [
            'company_id' => $company->id ?? null,
            'vector_id' => $vector->id ?? null,
            'country_name' => $instance['document']['sourceId']['country'],
            'country_id' => $country->id ?? null,
            'region_id' => $region->id ?? null,
            'entity_id' => $instance['id'],
            'event_type' => $instance['type'],
            'original_language' => $instance['document']['language'],
            'source' => $instance['document']['sourceId']['name'],
            'title' => $instance['document']['title'],
            'fragment' => $instance['fragment'],
            'fragment_hash' => md5($instance['fragment']),
            'link' => $instance['document']['url'],
            'positive_sentiment' => $instance['attributes']['general_positive'],
            'negative_sentiment' => $instance['attributes']['general_negative'],
            'published_at' => (new Carbon($instance['document']['published']))->toDateTimeString(),
        ]);
    }
}
