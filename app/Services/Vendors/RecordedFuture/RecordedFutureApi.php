<?php

namespace App\Services\Vendors\RecordedFuture;

use GuzzleHttp\Client;

class RecordedFutureApi
{
    /**
     * The URL to make queries to Recorded Future.
     *
     * @var string
     * */
    protected $baseUrl = 'https://api.recordedfuture.com/query?q=';

    /**
     * The access token to make a query to Recorded Future.
     *
     * @var string
     */
    protected $token;

    /**
     * The client to make the queries for Recorded Future.
     *
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Creates an instance of the RecordedFutureApi class.
     *
     * @param Client $client
     * @param string $token
     */
    public function __construct(Client $client, $token = '')
    {
        $this->client = $client;
        if (!$token) {
            $token = env('RECORDED_FUTURE_TOKEN');
        }
        $this->token = $token;
    }

    /**
     * Queries Recorded Future for all instances of an entity from a number of days back in time.
     *
     * @param string $entityId
     * @param int $daysBack
     * @param array $options
     * @return Response
     */
    public function queryInstancesForEntityDaily(string $entityId, int $daysBack = 7, array $options = []): Response
    {
        return $this->queryApi(['instance' => array_merge(
            $this->buildInstanceQueryOptionsForEntity($entityId, $daysBack, 'd'),
            $options
        )]);
    }

    /**
     * Queries Recorded Future for all instances of an entity from a number of hours back in time.
     *
     * @param string $entityId
     * @param int $hoursBack
     * @param array $options
     * @return Response
     */
    public function queryInstancesForEntityHourly(string $entityId, int $hoursBack = 1, array $options = []): Response
    {
        return $this->queryApi(['instance' => array_merge(
            $this->buildInstanceQueryOptionsForEntity($entityId, $hoursBack, 'h'),
            $options
        )]);
    }

    /**
     * @param string $entityId
     * @param int $measurement
     * @param string $timeMeasurement
     * @return array
     */
    protected function buildInstanceQueryOptionsForEntity(string $entityId, int $measurement, string $timeMeasurement): array
    {
        $defaultOptions = [
            'attributes' => [
                ['entity' => ['id' => $entityId]],
                [
                    'name' => ['general_positive', 'general_negative'],
                    'range' => ['gt' => 0]
                ]
            ],
            'time_range' => "-{$measurement}{$timeMeasurement} to +0{$timeMeasurement}",
        ];

        return $defaultOptions;
    }

    /**
     * @param array $entityIds
     * @return Response|null
     */
    public function getEntitiesByCodes(array $entityIds)
    {
        if (!empty($entityIds)) {
            $options = ['entity' => ['id' => $entityIds]];
            return $this->queryApi($options);
        }
        return null;
    }

    public function getInstanceByCodes(array $instanceIds)
    {
        if (!empty($instanceIds)) {
            $options = ['instance' => ['id' => $instanceIds]];
            return $this->queryApi($options);
        }
        return null;
    }

    protected function queryApi(array $options): Response
    {
        $options = [
            // The json key indicated the query parameters should be sent as JSON encoded.
            'json' => $this->assembleExtraOptions($options)
        ];

        $response = $this->client->get('https://api.recordedfuture.com/query?q=', $options);
        return new Response($response);
    }

    protected function assembleExtraOptions(array $options): array
    {
        $options[key($options)]['searchtype'] = 'scan';
        $options['token'] = $this->token;

        return $options;
    }
}
