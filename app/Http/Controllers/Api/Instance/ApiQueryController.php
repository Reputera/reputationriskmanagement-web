<?php

namespace App\Http\Controllers\Api\Instance;


use App\Http\Controllers\ApiController;
use App\Http\Requests\Instance\ApiInstanceQueryRequest;
use App\Http\Requests\Instance\ApiRiskScoreRequest;
use App\Http\Traits\PaginationTrait;
use App\Services\Instance\QueryBuilder;
use App\Transformers\Instance\InstanceTransformer;
use App\Http\Queries\Instance as InstanceQuery;

class ApiQueryController extends ApiController
{

    use PaginationTrait;

    /**
     * @api {post} /instances/ List instances
     * @apiName ListInstances
     * @apiDescription List instances based on query parameters.
     * @apiGroup Instances
     * @apiUse InstanceQuery
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
        return $this->respondWithArray($this->fractalPaginate($resultCollection, new InstanceTransformer()));
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

    /**
     * @api {post} /competitors-average-risk-score Risk Score
     * @apiName RiskScore
     * @apiDescription Return the risk score for a company, and inudstry average risk score.
     * @apiUse RiskScoreParams
     * @apiGroup Instances
     * @apiExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data":[
     *          {"company_risk_score":25},
     *          {"average_competitor_risk_score":10}
     *      ],
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     */
    /**
     * @param ApiRiskScoreRequest $request
     * @param InstanceQuery $query
     * @return \Illuminate\Http\JsonResponse
     */
    public function competitorsAverageRiskScore(ApiRiskScoreRequest $request, InstanceQuery $query)
    {
        return $this->respondWithArray([
            'company_risk_score' => $request->user()->company->averageRiskScore($query),
            'average_competitor_risk_score' => $request->user()->company->competitorsAverageRiskScore($query)
        ]);
    }

}