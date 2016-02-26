<?php

namespace App\Services\Vendors;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Quandl
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Endpoint for the Quandl API.
     *
     * @var string
     */
    protected $endpoint = 'https://www.quandl.com/api/v3/datasets';

    /**
     * The default database to query in Quandl.
     *
     * @var string
     */
    protected $database = 'WIKI';

    /**
     * The data type to return from the API calls.
     *
     * @var string
     */
    protected $returnDataType = 'json';

    /**
     * The dataset to use from Quandl
     *
     * @var string
     */
    protected $dataset = 'data';

    /**
     * Holds any error that may come back from the API
     *
     * @var string
     */
    protected $error = '';

    /**
     * Holds any error code that may come back from the API
     *
     * @var string
     */
    protected $errorCode = '';

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = env('STOCK_MARKET_API_KEY');
    }

    /**
     * Makes the call to the API.
     *
     * @param string $tickerSymbol 1 to 5 letter code that represents the stock.
     * @param string $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function get($tickerSymbol, $options = '')
    {
        $endpointSegments = [$this->endpoint, $this->database, $tickerSymbol];
        if ($this->dataset) {
            $endpointSegments[] = $this->dataset;
        }

        $endpoint = implode('/', $endpointSegments);
        try {
            if ($this->apiKey) {
                $options .= '&api_key='.$this->apiKey;
            }

            return $this->client->get($endpoint.'.'.$this->returnDataType.'?'.$options);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $this->handleError(json_decode($e->getResponse()->getBody(), true)['quandl_error']);
            }
            return false;
        }
    }

    public function latest($tickerSymbol)
    {
        if ($res = $this->get($tickerSymbol, 'limit=1&end_date='.date('Y-m-d'))) {
            $result = $this->getDataArrayFromResults(json_decode($res->getBody(), true)['dataset_data']);
            return current($result);
        };
        return false;
    }

    protected function getDataArrayFromResults(array $results)
    {
        $return = [];
        $columnNames = $results['column_names'];
        foreach ($results['data'] as $row) {
            $return[] = array_combine($columnNames, $row);
        }
        return $return;
    }

    protected function handleError(array $errorArray)
    {
        $errorCode = 'Unknown';
        if (array_key_exists('code', $errorArray) && $errorArray['code']) {
            $errorCode = $errorArray['code'];
        }

        $this->errorCode = $errorCode;

        switch ($errorCode) {
            case 'QELx01':
            case 'QELx02':
            case 'QELx03':
            case 'QELx04':
            case 'QELx05':
                $error = 'Too many requests';
                break;
            case 'QEAx01':
                $error = 'Service misconfigured.';
                break;
            case 'QEPx01':
            case 'QEPx02':
            case 'QEPx03':
            case 'QEPx04':
            case 'QEPx05':
                $error = 'Insufficient permissions.';
                break;
            case 'QESx01':
            case 'QESx02':
            case 'QESx03':
            case 'QESx04':
            case 'QESx05':
            case 'QECx01':
            case 'QECx02':
            case 'QECx03':
            case 'QECx05':
                $error = 'Improper Request.';
                break;
            default:
                $error = 'Unknown error.';
        }

        $this->error = $error;

        \Log::error('There was an error with the Quandl API', $errorArray);
    }
}
