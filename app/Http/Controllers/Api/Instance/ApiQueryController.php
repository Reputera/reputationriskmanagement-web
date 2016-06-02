<?php

namespace App\Http\Controllers\Api\Instance;


use App\Http\Controllers\ApiController;
use App\Http\Requests\Instance\ApiInstanceQueryRequest;
use App\Http\Requests\Instance\InstanceQueryRequest;
use App\Http\Traits\PaginationTrait;
use App\Services\Instance\QueryBuilder;
use App\Transformers\Instance\InstanceTransformer;

class ApiQueryController extends ApiController
{

    use PaginationTrait;

    /**
     * @api {get} /instances/ List instances
     * @apiName ListInstances
     * @apiDescription List instances based on query parameters.
     * @apiGroup Instances
     * @apiUse MultipleInstances
     * @apiUse PaginatedResults
     */
    /**
     * @param ApiInstanceQueryRequest $request
     * @param QueryBuilder $queryBuilder
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyInstances(ApiInstanceQueryRequest $request, QueryBuilder $queryBuilder)
    {
        $params = $request->getForQuery(['vectors_name', 'regions_name', 'fragment']);
        $params['companies_name'] = $request->user()->company->name;
        $resultCollection = $this->paginateBuilder(
            $queryBuilder->queryInstances($request, $params)
                ->with('countries.region'), $request
        );

        return $this->respondWithArray([
            'count' => $resultCollection->total(),
            'total_sentiment_score' => $resultCollection->total() ? (int)($resultCollection->sum('sentiment') / $resultCollection->total() * 100) : 0,
            'instances' => $this->fractalPaginate($resultCollection, new InstanceTransformer())
        ]);
    }

    /**
     * @param ApiInstanceQueryRequest $request
     * @param QueryBuilder $queryBuilder
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyRiskScore(ApiInstanceQueryRequest $request, QueryBuilder $queryBuilder)
    {
        $params = $request->getForQuery([
            'vectors_name', 'regions_name', 'hideFlagged', 'fragment'
        ]);
        $params['companies_name'] = $request->user()->company->name;
        $resultCollection = $queryBuilder->queryInstances($request, $params)->get();
        $resultCount = $resultCollection->count();
        return $this->respondWithArray([
            'risk_score' => $resultCount ? (int)($resultCollection->sum('risk_score') / $resultCount) : 0
        ]);
    }

}