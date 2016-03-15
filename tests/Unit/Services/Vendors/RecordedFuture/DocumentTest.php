<?php

namespace Unit\Services\Vendors\RecordedFuture;

use App\Services\Vendors\RecordedFuture\Document;
use App\Services\Vendors\RecordedFuture\Source;

class DocumentTest extends \TestCase
{
    /** @var Document */
    protected $document;

    protected $fields = [
        'id' => 'NpIPGf',
        'title' => '$has:Us Hasbro in buy area, gives stockholders 2-for-1 deal #Hasbro Inc http://t.co/GZqj2hW9wi #sp500',
        'language' => 'eng',
        'published' => '2015-03-11T15:27:22.000Z',
        'downloaded' => '2015-03-11T15:27:22.831Z',
        'indexed' => '2015-03-11T15:27:22.000Z',
        'url' => 'https://twitter.com/FinSentS_SP500/status/575679412219330560',
        'sourceId' => [
            'id' => 'BV5',
            'name' => 'Twitter',
            'description' => 'Twitter',
            'media_type' => 'JxSEtC',
            'country' => 'United States'
        ]
    ];

    public function setUp()
    {
        parent::setUp();
        $this->document = new Document($this->fields);
    }

    public function function_name_and_expected_data_provider()
    {
        return [
            ['getId', 'NpIPGf'],
            ['getTitle', '$has:Us Hasbro in buy area, gives stockholders 2-for-1 deal #Hasbro Inc http://t.co/GZqj2hW9wi #sp500'],
            ['getLanguage', 'eng'],
            ['getPublished', '2015-03-11T15:27:22.000Z'],
            ['getUrl', 'https://twitter.com/FinSentS_SP500/status/575679412219330560']
        ];
    }

    /** @dataProvider function_name_and_expected_data_provider */
    public function test_getting_fields_for_non_return_objects($function, $expectedResult)
    {
        $this->assertEquals($expectedResult, $this->document->{$function}());
    }

    public function test_get_source_gives_a_source_instance()
    {
        $this->assertInstanceOf(source::class, $this->document->getSource());
    }
}
