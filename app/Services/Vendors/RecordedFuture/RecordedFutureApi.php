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
     * @var string
     */
    protected $limit = '100';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $pageStart = '';

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

    public function queryInstancesForEntity(string $entityId, int $daysBack = 7, array $options = []): Response
    {
        $defaultOptions = [
            'attributes' => [
                ['entity' => ['id' => $entityId]],
                [
                    'name' => ['general_positive', 'general_negative'],
                    'range' => ['gt' => 0]
                ]
            ],
            'time_range' => "-{$daysBack}d to +{$daysBack}d",
        ];

        return $this->queryApi(['instance' => array_merge($defaultOptions, $options)]);
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
//        $options[key($options)]['searchtype'] = 'scan';
        $options['token'] = $this->token;

        return $options;
    }
}
