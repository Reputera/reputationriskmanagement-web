<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use Illuminate\Support\Collection as IlluminateCollection;

trait TransformerTrait
{
    /**
     * The default status code used for HTTP responses.
     *
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @var array Which includes should be returned in request.
     */
    protected $transformerIncludes = [];

    /**
     * @return Manager
     */
    public function fractal()
    {
        $fractal =  app(Manager::class);
        if (!empty($this->transformerIncludes)) {
            $fractal->parseIncludes($this->transformerIncludes);
        }
        return $fractal;
    }

    /**
     * Transforms a collection into an array using Fractal.
     *
     * @param $collection
     * @param $callback
     */
    public function transformCollection($collection, $callback)
    {
        $this->fractal()
            ->createData(new Collection($collection, $callback))
            ->toArray();
    }

    /**
     * Dynamically determines the response based on the item given.
     *
     * @param $thing IlluminateCollection|Model
     * @param $callback
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWith($thing, $callback)
    {
        if ($thing instanceof IlluminateCollection) {
            return $this->respondWithCollection($thing, $callback);
        }
        if ($thing instanceof Model) {
            return $this->respondWithItem($thing, $callback);
        }
        throw new \InvalidArgumentException('Object to be responded with must be Collection/Model');
    }

    /**
     * When given a single entity/model, creates a proper response.
     *
     * @param $item
     * @param $callback
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);
        $rootScope = $this->fractal()->createData($resource);
        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * When given a collection, it creates the proper response.
     *
     * @param $collection
     * @param $callback
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithCollection($collection, $callback)
    {
        $resource = new Collection($collection, $callback);
        $rootScope = $this->fractal()->createData($resource);
        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * @apiDefine PaginatedResults
     * @apiParam {Number{1-1000}} [limit] The maximum number of records to be retrieved with pagination.
     * @apiSuccessExample {json} Results with pagination:
     *      // Any endpoint will have the following "meta" key added to the response. Also the endpoint
     *      // may have the following parameter to determine the number of records returned.
     *
     *      "meta": [
     *          "pagination" => [
     *              "total" => 10,
     *              "count" => 3,
     *              "per_page" => 3,
     *              "current_page" => 2,
     *              "total_pages" => 4,
     *              "links" => [
     *                  "previous" => "http://optoview/api/{endpoint_url}?page=1"
     *                  "next" => "http://optoview.com/api/{endpoint_url}?page=3"
     *              ]
     *          ]
     *      ]
     */
    /**
     * @param $paginator
     * @param $callback
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithPagination($paginator, $callback)
    {
        $collection = $paginator->getCollection();
        $resource = new Collection($collection, $callback);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        $rootScope = $this->fractal()->createData($resource);
        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Gets the status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the status code.
     *
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Creates the desired "good" response for API consumption.
     *
     * @param array $array
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
        $arrayToResponse = [
            'data' => array_get($array, 'data') ?: $array,
            'status_code' => $this->statusCode,
            'message' => 'Success',
        ];

        if ($metaData = array_get($array, 'meta')) {
            $arrayToResponse['meta'] = $metaData;
        }

        return \Response::json($arrayToResponse, $this->statusCode, $headers);
    }
}
