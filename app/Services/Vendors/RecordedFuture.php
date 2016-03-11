<?php

namespace App\Services\Vendors;

use GuzzleHttp\Client;

class RecordedFuture
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
     * Creates an instance of the RecordedFuture class.
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
     * @return RecordedFuture
     */
    public function setLimit(int $newLimit): RecordedFuture
    {
        if ($newLimit) {
            $this->limit = (string)$newLimit;
        }

        return $this;
    }

    /**
     * @param $start
     * @return RecordedFuture
     */
    public function setPageStart($start): RecordedFuture
    {
        if ($start) {
            $this->pageStart = (string)$start;
        }

        return $this;
    }

    /**
     * Get the entity code for a company name
     *
     * @param string $name
     * @return string
     */
    public function entityCodeForCompany(string $name): string
    {
        $this->limit = 1;
        $options = ['entity' => ['name' => (string)$name, 'type' => 'Company']];
        $results = $this->queryApi($options);

        if ($results = array_get($results, 'entities')) {
            return current($results);
        }
        return '';
    }

    /**
     * Gets the continent entity from a country.
     *
     * @param string $name
     * @return array
     */
    public function continentFromCountry(string $name): array
    {
        $this->limit = 1;
        $options = ['entity' => ['name' => (string)$name, 'type' => 'Country']];
        $results = $this->queryApi($options);
        $entity = current(array_get($results, 'entities', []));
        if ($entity && $entityDetails = array_get($results, 'entity_details.'.$entity)) {
            $results = $this->getEntitiesByCodes(array_get($entityDetails, 'containers', []));
            if ($results = array_get($results, 'entity_details')) {
                foreach ($results as $entityId => $result) {
                    if (array_has($result, 'type') && $result['type'] == 'Continent') {
                        return [$entityId => $result];
                    }
                }
            }
        }
        return '';
    }

    public function instancesForEntity(string $entityId, int $daysBack = 7): array
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

    public function getEntitiesByCodes(array $entityIds): array
    {
        if (!empty($entityIds)) {
            $options = ['entity' => ['id' => $entityIds]];
            return $this->queryApi($options);
        }
        return [];
    }

//    public function getInstancesForCompanyName($name)
//    {
//        $options = [
//            'instance' => [
//                'attributes' => [
//                    'entity' => ['name' => (string)$name, 'type' => 'Company']
//                ]
//            ]
//        ];
//        return $this->queryApi($options);
//    }
//
//    public function getInstancesForEntityId($entityId)
//    {
//        $options = [
//            'instance' => [
//                'attributes' => [
//                    'entity' => ['id' => (string)$entityId]
//                ]
//            ]
//        ];
//        return $this->queryApi($options);
//    }

    protected function queryApi(array $options): array
    {
        $options = [
            // The json key indicated the query parameters should be sent as JSON encoded.
            'json' => $this->assembleExtraOptions($options)
        ];

        $response = $this->client->get('https://api.recordedfuture.com/query?q=', $options);
        return json_decode($response->getBody(), true);
    }

    protected function assembleExtraOptions(array $options): array
    {
        $queryType = key($options);
        if ($queryType == 'instance') {
            $options[$queryType] = $this->assembleLimitAndPageStart($options[$queryType]);
        }

        $options['token'] = $this->token;
        return $options;
    }

    protected function assembleLimitAndPageStart(array $options): array
    {
        if ($this->limit) {
            $options['limit'] = $this->limit;
        }

        if ($this->pageStart) {
            $options['page_start'] = $this->pageStart;
        }

        return $options;
    }
}
