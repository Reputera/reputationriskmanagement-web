<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture\Api;

use App\Services\Vendors\RecordedFuture\Api\Document;
use App\Services\Vendors\RecordedFuture\Api\Entity;
use App\Services\Vendors\RecordedFuture\Api\Instance;
use App\Services\Vendors\RecordedFuture\Api\InstanceAttributes;
use Tests\StubData\RecordedFuture\SingleInstance;

class InstanceTest extends \TestCase
{
    /** @var Document */
    protected $instance;

    public function setUp()
    {
        parent::setUp();
        $this->instance = new Instance(SingleInstance::get([
            'id' => 'GUwp9aBfdDn',
            'type' => 'RFEVEOrganizationRelationship',
            'fragment' => 'Lorem ipsum dolor sit amet, aliquam sit libero fringilla purus, aliqua ligula.'
        ])['instances'][0]);
    }

    public function function_name_and_expected_data_provider()
    {
        return [
            ['getId', 'GUwp9aBfdDn'],
            ['getType', 'RFEVEOrganizationRelationship'],
            ['getFragment', 'Lorem ipsum dolor sit amet, aliquam sit libero fringilla purus, aliqua ligula.'],
        ];
    }

    /** @dataProvider function_name_and_expected_data_provider */
    public function test_getting_populated_fields_for_non_return_objects($function, $expectedResult)
    {
        $this->assertEquals($expectedResult, $this->instance->{$function}());
    }

    public function test_getting_the_document_object()
    {
        $this->assertInstanceOf(Document::class, $this->instance->getDocument());
    }

    public function test_getting_the_instance_attributes_object()
    {
        $this->assertInstanceOf(InstanceAttributes::class, $this->instance->getAttributes());
    }

    public function test_getting_continent_called_the_recorded_future_api()
    {
        $entity1 = new Entity('123', ['name' => 'Some Name', 'type' => 'Country']);
        $entity2 = new Entity('123', ['name' => 'Some New Name', 'type' => 'Country']);
        $entity3 = new Entity('123', ['name' => 'Some New New Name', 'type' => 'Some Other Type']);

        $this->instance->setRelatedEntities([$entity1, $entity3, $entity2]);

        $results = $this->instance->getCountries();
        $this->assertInternalType('array', $results);
        $this->assertCount(2, $results);
        $this->assertInstanceOf(Entity::class, $results[0]);
        $this->assertEquals($entity1->getName(), $results[0]->getName());
        $this->assertInstanceOf(Entity::class, $results[1]);
        $this->assertEquals($entity2->getName(), $results[1]->getName());
    }
}
