<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture;

use App\Services\Vendors\RecordedFuture\Entity;
use App\Services\Vendors\RecordedFuture\RecordedFutureApi;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Tests\StubData\RecordedFuture\SingleEntity;

class RecordedFutureApiTest extends \TestCase
{
    /** @var RecordedFutureApi */
    protected $recordedFutureApi;

    /** @var ClientInterface|\Mockery\MockInterface */
    protected $mockedClient;

    /** @var GuzzleResponse|\Mockery\MockInterface */
    protected $mockedGuzzleResponse;

    /** @var string */
    protected $apiKey = 'someAPIToken';

    public function setUp()
    {
        parent::setUp();
        $this->mockedClient = \Mockery::mock(Client::class);
        $this->mockedGuzzleResponse = \Mockery::mock(GuzzleResponse::class);
        $this->recordedFutureApi = new RecordedFutureApi($this->mockedClient, $this->apiKey);
    }

    public function test_querying_with_no_options_and_default_days_used()
    {
        $this->mockedGuzzleResponse->shouldReceive('getBody')->once()->andReturn('{}');

        $entityId = 'EntityId';
        $this->mockedClient->shouldReceive('get')
            ->once()
            ->with(
                'https://api.recordedfuture.com/query?q=',
                [
                    'json' => [
                        'instance' => [
                            'attributes' => [
                                ['entity' => ['id' => $entityId]],
                                [
                                    'name' => ['general_positive', 'general_negative'],
                                    'range' => ['gt' => 0]
                                ]
                            ],
                            'time_range' => "-7d to +7d",
                        ],
                        'token' => 'someAPIToken',
                    ]
                ]
            )
            ->andReturn($this->mockedGuzzleResponse);

        $this->recordedFutureApi->queryInstancesForEntity($entityId);
    }

    public function test_querying_with_non_default_days()
    {
        $this->mockedGuzzleResponse->shouldReceive('getBody')->once()->andReturn('{}');

        $entityId = 'EntityId';
        $this->mockedClient->shouldReceive('get')
            ->once()
            ->with(
                'https://api.recordedfuture.com/query?q=',
                [
                    'json' => [
                        'instance' => [
                            'attributes' => [
                                ['entity' => ['id' => $entityId]],
                                [
                                    'name' => ['general_positive', 'general_negative'],
                                    'range' => ['gt' => 0]
                                ]
                            ],
                            'time_range' => "-1d to +1d",
                        ],
                        'token' => 'someAPIToken',
                    ]
                ]
            )
            ->andReturn($this->mockedGuzzleResponse);

        $this->recordedFutureApi->queryInstancesForEntity($entityId, 1);
    }

    public function test_querying_with_options_specified_number_of_days_to_process()
    {
        $this->mockedGuzzleResponse->shouldReceive('getBody')->once()->andReturn('{}');

        $entityId = 'EntityId';
        $this->mockedClient->shouldReceive('get')
            ->once()
            ->with(
                'https://api.recordedfuture.com/query?q=',
                [
                    'json' => [
                        'instance' => [
                            'attributes' => [
                                ['entity' => ['id' => $entityId]],
                                [
                                    'name' => ['general_positive', 'general_negative'],
                                    'range' => ['gt' => 0]
                                ]
                            ],
                            'time_range' => "-5d to +5d",
                            'limit' => 5,
                        ],
                        'token' => 'someAPIToken',
                    ]
                ]
            )
            ->andReturn($this->mockedGuzzleResponse);

        $this->recordedFutureApi->queryInstancesForEntity($entityId, 5, ['limit' => 5]);
    }

    public function test_getting_entites_by_ids()
    {
        $this->mockedGuzzleResponse->shouldReceive('getBody')->once()->andReturn('{}');

        $entityIds = ['1234', '5678'];
        $this->mockedClient->shouldReceive('get')
            ->once()
            ->with(
                'https://api.recordedfuture.com/query?q=',
                [
                    'json' => [
                        'entity' => ['id' => $entityIds],
                        'token' => 'someAPIToken',
                    ]
                ]
            )
            ->andReturn($this->mockedGuzzleResponse);

        $this->recordedFutureApi->getEntitiesByCodes($entityIds);
    }
}
