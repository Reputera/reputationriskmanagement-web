<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture;

use App\Services\Vendors\RecordedFuture\Document;
use App\Services\Vendors\RecordedFuture\Instance;
use App\Services\Vendors\RecordedFuture\InstanceAttributes;
use App\Services\Vendors\RecordedFuture\RecordedFutureApi;

class InstanceTest extends \TestCase
{
    /** @var Document */
    protected $instance;

    protected $fields = [
        'id' => 'GUwp9aBfdDn',
        'type' => 'RFEVEOrganizationRelationship',
        'cluster_id' => 'C3poAeCXtyk',
        'cluster_ids'=>[
            'C3poAeCXtyk'
        ],
        'start' => '2015-03-10T23:25:08.000Z',
        'stop' => '2015-03-10T23:25:08.000Z',
        'tagged_fragment' => '$has:<i id=GUwp9aBfdDn><e id=KHV7oS>Us Hasbro</e> in buy area, gives stockholders 2-for-1 deal <e id=KerzW8>#Hasbro</e></i> Inc <e id=url:http://tinyurl.com/pohcymh>http://t.co/GZqj2hW9wi</e> <e id=KezJ4l>#sp500</e>.',
        'fragment' => '$has:Us Hasbro in buy area, gives stockholders 2-for-1 deal #Hasbro Inc http://t.co/GZqj2hW9wi #sp500.',
        'item_fragment' => 'Us Hasbro in buy area, gives stockholders 2-for-1 deal #Hasbro',
        'precision' => 'ms',
        'time_type' => 'in',
        'document'=> [
            'id' => 'NpIPGf',
            'title' => '$has:Us Hasbro in buy area, gives stockholders 2-for-1 deal #Hasbro Inc http://t.co/GZqj2hW9wi #sp500',
            'language' => 'eng',
            'published' => '2015-03-11T15:27:22.000Z',
            'downloaded' => '2015-03-11T15:27:22.831Z',
            'indexed' => '2015-03-11T15:27:22.000Z',
            'url' => 'https://twitter.com/FinSentS_SP500/status/575679412219330560',
            'sourceId'=> [
                'id' => 'BV5',
                'name' => 'Twitter',
                'description' => 'Twitter',
                'media_type' => 'JxSEtC',
                'country' => 'United States'
            ]
        ],
        'attributes'=> [
            'general_negative'=>0,
            'function' => 'id',
            'user_data' => [
                'friends_count'=>1518,
                'statuses_count'=>23792,
                'followers_count'=>924
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
            'positive'=>0,
            'document_url' => 'url:https://twitter.com/FinSentS_SP500/status/575679412219330560',
            'sentiments'=> [
                'general_positive'=>0,
                'violence'=>0,
                'activism'=>0,
                'general_negative'=>0,
                'negative'=>0,
                'positive'=>0,
                'profanity'=>0
            ],
            'violence'=>0,
            'original_published' => '2015-03-10T23:25:08.000Z',
            'document_position'=> [
                'latitude'=>40.71427,
                'longitude'=>-74.00597
            ],
            'document_external_id' => '575679412219330560',
            'binning_id' => 'Bb_AxyjrEoH',
            'entities'=>[
                'KezJ4l',
                'url:http://tinyurl.com/pohcymh',
                'KHV7oS',
                'KerzW8'
            ],
            'meta_type' => 'type:RFEVEOrganizationRelationship',
            'document_location' => 'B_RYB',
            'general_positive'=>0,
            'topics' => [
                'KPzZAT'
            ],
            'negative'=>0,
            'document_original_id' => '575437259090104320',
            'fragment_count'=>1
        ]
    ];

    public function setUp()
    {
        parent::setUp();
        $this->instance = new Instance($this->fields);
    }

    public function function_name_and_expected_data_provider()
    {
        return [
            ['getId', 'GUwp9aBfdDn'],
            ['getType', 'RFEVEOrganizationRelationship'],
            ['getFragment', '$has:Us Hasbro in buy area, gives stockholders 2-for-1 deal #Hasbro Inc http://t.co/GZqj2hW9wi #sp500.'],
            ['getCountry', 'United States'],
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
        $mockApi = \Mockery::mock(RecordedFutureApi::class);
        $mockApi->shouldReceive('continentFromCountry')->once()->with('United States');

        app()->instance(RecordedFutureApi::class, $mockApi);

        $this->instance->getContinent();
    }
}
