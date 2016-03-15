<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture;

use App\Services\Vendors\RecordedFuture\InstanceAttributes;
use App\Services\Vendors\RecordedFuture\RecordedFutureApi;

class InstanceAttributesTest extends \TestCase
{
    /** @var InstanceAttributes */
    protected $instanceAttributes;

    protected $fields = [
        'general_negative' => 2.23,
        'function' => 'id',
        'user_data' => [
            'friends_count' => 1518,
            'statuses_count' => 23792,
            'followers_count' => 924
        ],
        'authors' => [
            'LyXww0'
        ],
        'document_category' => 'Business_Finance',
        'canonic_id' => 'Bb_AxyjrEoH',
        'document_position_source' => 'author',
        'analyzed' => '2015-03-11T15:27:23.912Z',
        'partner2' => 'B_PVb',
        'partner1' => 'KHV7oS',
        'relationship_indicator' => 'deal',
        'positive' => 0,
        'document_url' => 'url:https://twitter.com/FinSentS_SP500/status/575679412219330560',
        'sentiments' => [
            'general_positive' => 1.25,
            'violence' => 0,
            'activism' => 0,
            'general_negative' => 2.23,
            'negative' => 0,
            'positive' => 0,
            'profanity' => 0
        ],
        'violence' => 0,
        'original_published' => '2015-03-10T23:25:08.000Z',
        'document_position' => [
            'latitude' => 40.71427,
            'longitude' => -74.00597
        ],
        'document_external_id' => '575679412219330560',
        'binning_id' => 'Bb_AxyjrEoH',
        'entities' => [
            'KezJ4l',
            'url:http://tinyurl.com/pohcymh',
            'KHV7oS',
            'KerzW8'
        ],
        'meta_type' => 'type:RFEVEOrganizationRelationship',
        'document_location' => 'B_RYB',
        'general_positive' => 1.25,
        'topics' => [
            'KPzZAT'
        ],
        'negative' => 0,
        'document_original_id' => '575437259090104320',
        'fragment_count' => 1
    ];

    public function setUp()
    {
        parent::setUp();
        $this->instanceAttributes = new InstanceAttributes($this->fields);
    }

    public function function_name_and_expected_populated_data_provider()
    {
        return [
            ['getPositiveSentiment', 1.25],
            ['getNegativeSentiment', 2.23],
        ];
    }

    /** @dataProvider function_name_and_expected_populated_data_provider */
    public function test_getting_populated_fields_for_non_return_objects($function, $expectedResult)
    {
        $this->assertEquals($expectedResult, $this->instanceAttributes->{$function}());
    }

    public function test_getting_entites_when_they_should_exists()
    {
        $mockApi = \Mockery::mock(RecordedFutureApi::class);
        $mockApi->shouldReceive('getEntitiesByCodes')->once()->with($this->fields['entities'])->andReturnSelf();
        $mockApi->shouldReceive('getEntities')->once()->withNoArgs()->andReturn([]);

        app()->instance(RecordedFutureApi::class, $mockApi);

        $this->instanceAttributes->getEntities();
    }

    public function test_getting_entites_when_they_should_not_exists()
    {
        $mockApi = \Mockery::mock(RecordedFutureApi::class);
        $mockApi->shouldReceive('getEntitiesByCodes')->once()->with($this->fields['entities'])->andReturnNull();
        $mockApi->shouldReceive('getEntities')->never();

        app()->instance(RecordedFutureApi::class, $mockApi);

        $this->assertInternalType('array', $this->instanceAttributes->getEntities());
    }
}
