<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture\Api;

use App\Services\Vendors\RecordedFuture\Api\InstanceAttributes;
use App\Services\Vendors\RecordedFuture\Api\RecordedFutureApi;
use Tests\StubData\RecordedFuture\SingleInstance;

class InstanceAttributesTest extends \TestCase
{
    /** @var InstanceAttributes */
    protected $instanceAttributes;

    public function setUp()
    {
        parent::setUp();
        $this->instanceAttributes = new InstanceAttributes(SingleInstance::get()['instances'][0]['attributes']);
    }

    public function function_name_and_expected_populated_data_provider()
    {
        return [
            ['getPositiveSentiment', 0.04477611940298507],
            ['getNegativeSentiment', 0],
        ];
    }

    /** @dataProvider function_name_and_expected_populated_data_provider */
    public function test_getting_populated_fields_for_non_return_objects($function, $expectedResult)
    {
        $this->assertEquals($expectedResult, $this->instanceAttributes->{$function}());
    }

    public function test_getting_entity_codes()
    {
        $this->assertEquals(['I6JBTy', 'I2Endo'], $this->instanceAttributes->getEntityCodes());
    }
}
