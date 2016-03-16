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

    /**
     * @param int $newLimit
     * @return RecordedFutureApi
     */
    public function setLimit(int $newLimit): RecordedFutureApi
    {
        if ($newLimit) {
            $this->limit = (string)($newLimit > 1000) ? 1000 : $newLimit;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param $start
     * @return RecordedFutureApi
     */
    public function setPageStart($start): RecordedFutureApi
    {
        if ($start) {
            $this->pageStart = (string)$start;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getPageStart(): int
    {
        return $this->pageStart;
    }

    /**
     * Gets the continent entity from a country.
     *
     * @param string $name
     * @return Entity|null
     */
    public function continentFromCountry(string $name)
    {
        if (!$name) {
            return null;
        }

        $this->limit = 1;
        $options = ['entity' => ['name' => (string)$name, 'type' => 'Country']];

        if ($entity = $this->queryApi($options)->getEntity()) {
            $containers = $this->getEntitiesByCodes($entity->getContainers())
                ->getEntities();
            foreach ($containers as $containerEntity) {
                if ($containerEntity->getType() == 'Continent') {
                    return $containerEntity;
                }
            }
        }

        return null;
    }

    public function queryInstancesForEntity(string $entityId, int $daysBack = 7): Response
    {
        $options = [
            'instance' => [
                'attributes' => [
                    ['entity' => ['id' => $entityId]],
                    [
                        'name' => ['general_positive', 'general_negative'],
                        'range' => ['gt' => 0]
                    ]
                ],
                'time_range' => "-{$daysBack}d to +{$daysBack}d",
            ],
        ];

        return $this->queryApi($options);
    }

    public function getIndustriesForEntity(string $entityId): array
    {
        $results = $this->getEntitiesByCodes([$entityId]);

        if ($industryCodes = array_get($results, 'entity_details.'.$entityId.'.industries')) {
            return $this->getEntitiesByCodes($industryCodes);
        }

        return [];
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
        $queryType = key($options);
        $options['token'] = $this->token;
        $options[$queryType]['searchtype'] = 'scan';
        if ($this->limit) {
            $options[$queryType]['limit'] = $this->limit;
        }

        if ($this->pageStart) {
            $options[$queryType]['page_start'] = $this->pageStart;
        }

        return $options;
    }
}
