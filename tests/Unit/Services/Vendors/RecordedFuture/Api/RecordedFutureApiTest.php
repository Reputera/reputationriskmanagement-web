<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture\Api;

use App\Services\Vendors\RecordedFuture\Api\Entity;
use App\Services\Vendors\RecordedFuture\Api\RecordedFutureApi;
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

    protected $queryUrl = 'https://api.recordedfuture.com/query?q=';

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
                $this->queryUrl,
                $this->buildJsonForGuzzleRFInstanceQuery($entityId, 7, 'd')
            )
            ->andReturn($this->mockedGuzzleResponse);

        $this->recordedFutureApi->queryInstancesForEntityDaily($entityId);
    }

    public function test_querying_with_no_options_and_default_hours_used()
    {
        $this->mockedGuzzleResponse->shouldReceive('getBody')->once()->andReturn('{}');

        $entityId = 'EntityId';
        $this->mockedClient->shouldReceive('get')
            ->once()
            ->with(
                $this->queryUrl,
                $this->buildJsonForGuzzleRFInstanceQuery($entityId, 1, 'h')
            )
            ->andReturn($this->mockedGuzzleResponse);

        $this->recordedFutureApi->queryInstancesForEntityHourly($entityId);
    }

    public function test_querying_with_non_default_days()
    {
        $this->mockedGuzzleResponse->shouldReceive('getBody')->once()->andReturn('{}');

        $entityId = 'EntityId';
        $this->mockedClient->shouldReceive('get')
            ->once()
            ->with(
                $this->queryUrl,
                $this->buildJsonForGuzzleRFInstanceQuery($entityId, 99, 'd')
            )
            ->andReturn($this->mockedGuzzleResponse);

        $this->recordedFutureApi->queryInstancesForEntityDaily($entityId, 99);
    }

    public function test_querying_with_non_default_hours()
    {
        $this->mockedGuzzleResponse->shouldReceive('getBody')->once()->andReturn('{}');

        $entityId = 'EntityId';
        $this->mockedClient->shouldReceive('get')
            ->once()
            ->with(
                $this->queryUrl,
                $this->buildJsonForGuzzleRFInstanceQuery($entityId, 99, 'h')
            )
            ->andReturn($this->mockedGuzzleResponse);

        $this->recordedFutureApi->queryInstancesForEntityHourly($entityId, 99);
    }

    public function test_querying_with_options_specified_number_of_days_to_process()
    {
        $this->mockedGuzzleResponse->shouldReceive('getBody')->once()->andReturn('{}');

        $entityId = 'EntityId';
        $this->mockedClient->shouldReceive('get')
            ->once()
            ->with(
                $this->queryUrl,
                $this->buildJsonForGuzzleRFInstanceQuery($entityId, 5, 'd', ['limit' => 5])
            )
            ->andReturn($this->mockedGuzzleResponse);

        $this->recordedFutureApi->queryInstancesForEntityDaily($entityId, 5, ['limit' => 5]);
    }

    public function test_querying_with_options_specified_number_of_hours_to_process()
    {
        $this->mockedGuzzleResponse->shouldReceive('getBody')->once()->andReturn('{}');

        $entityId = 'EntityId';
        $this->mockedClient->shouldReceive('get')
            ->once()
            ->with(
                $this->queryUrl,
                $this->buildJsonForGuzzleRFInstanceQuery($entityId, 5, 'h', ['something' => 9])
            )
            ->andReturn($this->mockedGuzzleResponse);

        $this->recordedFutureApi->queryInstancesForEntityHourly($entityId, 5, ['something' => 9]);
    }

    public function test_getting_entities_by_ids()
    {
        $this->mockedGuzzleResponse->shouldReceive('getBody')->once()->andReturn('{}');

        $entityIds = ['1234', '5678'];
        $this->mockedClient->shouldReceive('get')
            ->once()
            ->with(
                $this->queryUrl,
                [
                    'json' => [
                        'entity' => ['id' => $entityIds, 'searchtype' => 'scan',],
                        'token' => 'someAPIToken',
                    ]
                ]
            )
            ->andReturn($this->mockedGuzzleResponse);

        $this->recordedFutureApi->getEntitiesByCodes($entityIds);
    }

    protected function buildJsonForGuzzleRFInstanceQuery($entityId, $measurement, $measurementUnits, array $options = [])
    {
        $returnArray = [
            'json' => [
                'instance' => [
                    'attributes' => [
                        ['entity' => ['id' => $entityId]],
                    ],
                ],
                'token' => $this->apiKey,
            ]
        ];

        if ($options) {
            $returnArray['json']['instance'] = array_merge($returnArray['json']['instance'], $options);
        }

        $returnArray['json']['instance']['time_range'] = "-{$measurement}{$measurementUnits} to +0{$measurementUnits}";
        $returnArray['json']['instance']['searchtype'] = 'scan';

        return $returnArray;
    }
}
