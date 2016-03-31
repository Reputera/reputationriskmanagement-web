<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture\Api;

use App\Services\Vendors\RecordedFuture\Api\Entity;
use Tests\StubData\RecordedFuture\SingleEntity;

class EntityTest extends \TestCase
{
    /** @var Entity */
    protected $entity;

    public function setUp()
    {
        parent::setUp();
        $responseArray = SingleEntity::getCompany([
            'entity_id' => '123456789',
            'entity_name' => 'Some Name',
            'entity_hits' => '451682344',
            'entity_containers_array' => ['someString']
        ])['entity_details'];
        $this->entity = new Entity(key($responseArray), current($responseArray));
    }

    public function function_name_and_expected_populated_data_provider()
    {
        return [
            ['getId', '123456789'],
            ['getName', 'Some Name'],
            ['getHits', 451682344],
            ['getType', 'Company'],
            ['getContainerCodes', ['someString']]
        ];
    }

    /** @dataProvider function_name_and_expected_populated_data_provider */
    public function test_getting_populated_fields_for_non_return_objects($function, $expectedResult)
    {
        $this->assertEquals($expectedResult, $this->entity->{$function}());
    }

    public function function_name_and_expected_unpopulated_data_provider()
    {
        $populatedData = $this->function_name_and_expected_populated_data_provider();
        $populatedData[0][1] = '';
        $populatedData[1][1] = '';
        $populatedData[2][1] = 0;
        $populatedData[3][1] = '';
        $populatedData[4][1] = [];
        return $populatedData;
    }

    /** @dataProvider function_name_and_expected_unpopulated_data_provider */
    public function test_getting_missing_fields_for_non_return_objects($function, $expectedOutput)
    {
        $entity = new Entity('', []);
        $this->assertEquals($expectedOutput, $entity->{$function}());
    }
}
