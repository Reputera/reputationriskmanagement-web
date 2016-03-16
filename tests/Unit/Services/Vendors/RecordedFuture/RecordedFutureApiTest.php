<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture;

use App\Services\Vendors\RecordedFuture\Entity;
use App\Services\Vendors\RecordedFuture\RecordedFutureApi;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class RecordedFutureApiTest extends \TestCase
{
    /** @var RecordedFutureApi */
    protected $recordedFutureApi;

    /** @var ClientInterface|\Mockery\MockInterface */
    protected $mockedClient;

    /** @var string */
    protected $apiKey = 'someAPIToken';

    protected $responseCountry = [
        'count' => [
            'entities' => [
                'returned' => 1,
                'total' => 0
            ]
        ],
        'next_page_start' => '0',
        'status' => 'SUCCESS',
        'entities' => [
            'B_FAG'
        ],
        'entity_details' => [
            'B_FAG' => [
                'name' => 'United States',
                'hits' => 451742011,
                'type' => 'Country',
                'external_id' => '188021635',
                'containers' => [
                    'B_GRZ'
                ],
                'longname' => 'United States',
                'external_links' => [
                    'wikipedia' => [
                        'id' => 'United_States'
                    ]
                ],
                'alias' => [
                    '',
                    'us',
                    '188021635',
                    'usa',
                    'u.s.',
                    'united states',
                    'u.s.a.',
                    'united states of america'
                ],
                'features' => [
                    'J3HWz_'
                ],
                'curated' => 1,
                'meta_type' => 'type:Country',
                'pos' => [
                    'latitude' => 39.76,
                    'longitude' => -98.5
                ]
            ]
        ]
    ];

    protected $responseContinent = [
        'count' => [
            'entities' => [
                'returned' => 1,
                'total' => 0
            ]
        ],
        'next_page_start' => '0',
        'status' => 'SUCCESS',
        'entities' => [
            'B_GRZ'
        ],
        'entity_details' => [
            'B_GRZ' => [
                'name' => 'North America',
                'hits' => 9094951,
                'type' => 'Continent',
                'alias' => [
                    'Norteamerica',
                    'América do Norte',
                    'Северна Америка',
                    'Amérique du Nord',
                    'Norður-Ameríka',
                    'Bac My',
                    'America settentrionale',
                    'Abya Yala',
                    'Amèrica del Nord',
                ],
                'features' => [
                    'J3HW2Y'
                ],
                'curated' => 1,
                'meta_type' => 'type:Continent',
                'pos' => [
                    'latitude' => 46.07323,
                    'longitude' => -100.54688
                ]
            ]
        ]
    ];
    
    public function setUp()
    {
        parent::setUp();
        $this->mockedClient = \Mockery::mock(Client::class);
        $this->recordedFutureApi = new RecordedFutureApi($this->mockedClient, $this->apiKey);
    }

    public function test_setting_request_limit()
    {
        $this->assertInstanceOf(RecordedFutureApi::class, $this->recordedFutureApi->setLimit(1000));
        $this->assertEquals(1000, $this->recordedFutureApi->getLimit());
    }

    public function test_setting_request_limit_above_threshold()
    {
        $this->assertInstanceOf(RecordedFutureApi::class, $this->recordedFutureApi->setLimit(1001));
        $this->assertEquals(1000, $this->recordedFutureApi->getLimit());
    }

    public function test_limit_default()
    {
        $this->assertEquals(100, $this->recordedFutureApi->getLimit());
    }

    public function test_setting_page_start()
    {
        $this->assertInstanceOf(RecordedFutureApi::class, $this->recordedFutureApi->setPageStart(10));
        $this->assertEquals(10, $this->recordedFutureApi->getPageStart());
    }

    public function test_getting_the_continent_when_a_blank_string_is_given()
    {
        $this->assertEmpty($this->recordedFutureApi->continentFromCountry(''));
    }

    public function test_getting_the_continent()
    {
        $mockedGuzzleClientForCountry = \Mockery::mock(GuzzleResponse::class);
        $mockedGuzzleClientForCountry->shouldReceive('getBody')->once()->andReturn(json_encode($this->responseCountry));
        $this->mockedClient->shouldReceive('get')
            ->once()
            ->with(
                'https://api.recordedfuture.com/query?q=',
                [
                    'json' => [
                        'entity' => [
                            'name' => 'testing',
                            'type' => 'Country',
                        ],
                        'token' => 'someAPIToken',
                        'limit' => 1,
                    ]
                ]
            )
            ->andReturn($mockedGuzzleClientForCountry);

        $mockedGuzzleClientForContinent = \Mockery::mock(GuzzleResponse::class);
        $mockedGuzzleClientForContinent->shouldReceive('getBody')->once()->andReturn(json_encode($this->responseContinent));
        $this->mockedClient->shouldReceive('get')
            ->once()
            ->with(
                'https://api.recordedfuture.com/query?q=',
                [
                    'json' => [
                        'entity' => [
                            'id' => [
                                'B_GRZ',
                            ],
                        ],
                        'token' => 'someAPIToken',
                        'limit' => 1,
                    ]
                ]
            )
            ->andReturn($mockedGuzzleClientForContinent);

        $this->assertInstanceOf(Entity::class, $this->recordedFutureApi->continentFromCountry('testing'));
    }

    public function test_getting_the_continet_when_there_is_no_record_for_it()
    {
        $mockedGuzzleClientForCountry = \Mockery::mock(GuzzleResponse::class);
        $mockedGuzzleClientForCountry->shouldReceive('getBody')->once()->andReturn('{}');
        $this->mockedClient->shouldReceive('get')
            ->once()
            ->with(
                'https://api.recordedfuture.com/query?q=',
                [
                    'json' => [
                        'entity' => [
                            'name' => 'testing',
                            'type' => 'Country',
                        ],
                        'token' => 'someAPIToken',
                        'limit' => 1,
                    ]
                ]
            )
            ->andReturn($mockedGuzzleClientForCountry);

        $this->assertNull($this->recordedFutureApi->continentFromCountry('testing'));
    }

//    public function companyCodeDataProvider()
//    {
//        $entityArray = SingleEntity::getCompany(['entity_name' => 'Hasbro'], false);
//        $jsonEncodedArray = json_encode($entityArray);
//        return [
//            ['Hasbro', current($entityArray['entities']), $jsonEncodedArray],
//            ['SomeCompany', '', EmptyEntity::get()],
//        ];
//    }
//
//    /** @dataProvider companyCodeDataProvider */
//    public function test_getting_company_code($companyName, $expectedResults, $apiReturnData)
//    {
//        $expectedQueryParams = [
//            'json' => [
//                'entity' => [
//                    'name' => $companyName,
//                    'type' => 'Company',
//                ],
//                'token' => $this->apiKey
//            ]
//        ];
//
//        $this->assertMockedApiCall($expectedQueryParams, $apiReturnData);
//
//        $this->assertEquals(
//            $expectedResults,
//            $this->rfApi->entityCodeForCompany($companyName)
//        );
//    }
//
//    protected function assertMockedApiCall(array $expectedQueryParams, $returnData)
//    {
//        $this->mockedClient
//            ->shouldReceive('get')
//            ->once()
//            ->with('https://api.recordedfuture.com/query?q=', $expectedQueryParams)
//            ->andReturnSelf();
//
//        $this->mockedClient
//            ->shouldReceive('getBody')
//            ->withNoArgs()
//            ->andReturn($returnData);
//    }
}
