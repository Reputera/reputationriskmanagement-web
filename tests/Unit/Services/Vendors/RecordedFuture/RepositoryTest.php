<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture;

use App\Entities\Company;
use App\Entities\Country;
use App\Entities\Region;
use App\Entities\Vector;
use App\Entities\VectorEventType;
use App\Services\Vendors\RecordedFuture\Entity;
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

    public function test_no_saving_of_duplicate_records()
    {
        $singleInstance = SingleInstance::get();
        $instance = new Instance($singleInstance['instances'][0]);
        factory(\App\Entities\Instance::class)->create(['fragment_hash' => md5($instance->getFragment())]);
        $company = factory(Company::class)->create();

        $this->assertFalse($this->repo->saveInstanceForCompany($instance, $company));
    }

    public function test_no_saving_of_duplicate_recorddddds()
    {
        $singleInstance = SingleInstance::get();
        $singleInstance['instances'][0]['attributes']['general_negative'] =
            $singleInstance['instances'][0]['attributes']['general_positive'];
        $instance = new Instance($singleInstance['instances'][0]);

        $company = factory(Company::class)->create();

        $this->assertFalse($this->repo->saveInstanceForCompany($instance, $company));
    }

    public function test_saving_an_instance_when_country_cannot_be_found()
    {
        $eventType = factory(VectorEventType::class)->create();

        $singleInstance = SingleInstance::get(['type' => $eventType->event_type]);

        $instance = new Instance($singleInstance['instances'][0]);
        $company = factory(Company::class)->create();

        $this->repo->saveInstanceForCompany($instance, $company);

        $this->assertInstanceInDb($company, $singleInstance['instances'][0], $eventType->vector);
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
        $eventType = factory(VectorEventType::class)->create();
        $singleInstance = SingleInstance::get(['type' => $eventType->event_type]);

        $country = factory(Country::class)->create(['entity_id' => 'B_FAG']);

        $instance = new Instance($singleInstance['instances'][0]);
        $instance->setRelatedEntities([new Entity('B_FAG', $singleInstance['entities']['B_FAG'])]);
        $company = factory(Company::class)->create();

        $this->repo->saveInstanceForCompany($instance, $company);

        $this->assertInstanceInDb($company, $singleInstance['instances'][0], $eventType->vector, $country);
    }

    protected function assertInstanceInDb(Company $company, array $instance, Vector $vector = null, Country $country = null, Region $region = null)
    {
        $this->seeInDatabase('instances', [
            'company_id' => $company->id ?? null,
            'vector_id' => $vector->id ?? null,
            'entity_id' => $instance['id'],
            'type' => $instance['type'],
            'start' => (new Carbon($instance['start']))->toDateTimeString(),
            'language' => $instance['document']['language'],
            'source' => $instance['document']['sourceId']['name'],
            'title' => $instance['document']['title'],
            'fragment' => $instance['fragment'],
            'fragment_hash' => md5($instance['fragment']),
            'link' => $instance['document']['url'],
            'sentiment' => (-$instance['attributes']['general_negative'] + $instance['attributes']['general_positive']),
            'positive_sentiment' => $instance['attributes']['general_positive'],
            'negative_sentiment' => $instance['attributes']['general_negative'],
        ]);

        if ($country) {
            $instanceId = \DB::table('instances')->where('entity_id', $instance['id'])->value('id');
            $this->seeInDatabase('instance_country', [
                'instance_id' => $instanceId,
                'country_id' => $country->id
            ]);
        }
    }
}
