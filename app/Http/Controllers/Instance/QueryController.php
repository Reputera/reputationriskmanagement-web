<?php

namespace App\Http\Controllers\Instance;

use App\Entities\Company;
use App\Http\Controllers\Controller;
use App\Http\Queries\Instance as InstanceQuery;
use App\Http\Requests\Instance\InstanceQueryRequest;
use App\Http\Requests\Instance\RiskChangeRequest;
use App\Http\Requests\Instance\RiskScoreRequest;
use App\Http\Traits\PaginationTrait;
use App\Services\Instance\QueryBuilder;
use App\Transformers\Instance\InstanceTransformer;
use Carbon\Carbon;
use League\Csv\Writer;

class QueryController extends Controller
{
    use PaginationTrait;

    /**
     * @api {get} /instance/ List instances
     * @apiName ListInstances
     * @apiDescription List instances based on query parameters.
     * @apiGroup Instances
     * @apiUse InstanceQuery
     * @apiUse MultipleInstances
     * @apiUse PaginatedResults
     */
    /**
     * @param InstanceQueryRequest $request
     * @param QueryBuilder $queryBuilder
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInstances(InstanceQueryRequest $request, QueryBuilder $queryBuilder)
    {
        $resultCollection = $this->paginateBuilder($queryBuilder->queryInstances($request, $request->getForQuery([
            'vectors_name', 'companies_name', 'regions_name', 'fragment'
            ]))->with('countries.region'), $request
        );

        return $this->respondWithArray($this->fractalPaginate($resultCollection, new InstanceTransformer()));
    }

    /**
     * @param InstanceQueryRequest $request
     * @param QueryBuilder $queryBuilder
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRiskScore(InstanceQueryRequest $request, QueryBuilder $queryBuilder)
    {
        $resultCollection = $queryBuilder->queryInstances($request, $request->getForQuery([
            'vectors_name', 'companies_name', 'regions_name', 'hideFlagged', 'fragment'
        ]))->get();
        $resultCount = $resultCollection->count();
        return $this->respondWithArray([
            'risk_score' => $resultCount ? (int)round(($resultCollection->sum('risk_score') / $resultCount)) : 0
        ]);
    }

    /**
     * @param InstanceQueryRequest $request
     * @param QueryBuilder $queryBuilder
     */
    public function getInstancesCsv(InstanceQueryRequest $request, QueryBuilder $queryBuilder)
    {
        $resultCollection = $queryBuilder->queryInstances($request, $request->getForQuery([
            'vectors_name', 'companies_name', 'regions_name', 'hideFlagged', 'fragment'
        ]))->get();
        $instances = $this->fractalizeCollection($resultCollection, new InstanceTransformer());
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(array_keys(array_get($instances, 'data.0')));
        $csv->insertAll($instances['data']);
        $csv->output($request->get('companies_name') . '.csv');
        die;
    }

    /**
     * @api {post} /competitors-average-risk-score Risk Score
     * @apiName RiskScore
     * @apiDescription Return the risk score for a company, and industry average risk score.
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
     * @apiExample {json} Success-Response-With-NA-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data":[
     *          {"company_risk_score":"N/A"},
     *          {"average_competitor_risk_score":"N/A"}
     *      ],
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     */
    /**
     * @param RiskScoreRequest $request
     * @param InstanceQuery $query
     * @return \Illuminate\Http\JsonResponse
     */
    public function competitorsAverageRiskScore(RiskScoreRequest $request, InstanceQuery $query)
    {
        $companyRiskScore = '';
        $competitorRiskScore = '';

        /** @var Company $company */
        if ($company = Company::whereName($request->get('company_name'))->first()) {
            $companyRiskScore = $company->averageRiskScore($query);
            $competitorRiskScore = $company->competitorsAverageRiskScore($query);
        }

        return $this->respondWithArray([
            'company_risk_score' => $companyRiskScore,
            'average_competitor_risk_score' => $competitorRiskScore
        ]);
    }

    /**
     * @api {get} /risk-score-change Risk Score Change
     * @apiName RiskScoreChange
     * @apiDescription Return the risk score change over a period of time defined by start/end_datetime as a whole number percent.
     * @apiUse RiskChangeParams
     * @apiGroup Risk Score
     * @apiExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data":[
     *          {"change_percent":25},
     *      ],
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     *  {json} Success-Response-With-NA-Return:
     *  HTTP/1.1 200 OK
     *  {
     *      "data":[
     *          {"change_percent":"N/A"},
     *      ],
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     */
    /**
     * @param RiskChangeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRiskChange(RiskChangeRequest $request)
    {
        return $this->respondWithArray([
            'change_percent' => $request->user()->company->reputationChangeBetweenDates(
                new Carbon($request->get('start_datetime')),
                new Carbon($request->get('end_datetime'))
            )
        ]);
    }

    /**
     * @api {get} /industry-risk-score-change Industry Risk Score Change
     * @apiName CompetitorRiskScoreChange
     * @apiDescription Return the risk score change for competitors over a period of time defined by start/end_datetime as a whole number percent.
     * @apiUse RiskChangeParams
     * @apiGroup Risk Score
     * @apiExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data":[
     *          {"change_percent":25},
     *      ],
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     * @apiExample {json} Success-Response-With-NA-Return:
     *  HTTP/1.1 200 OK
     *  {
     *      "data":[
     *          {"change_percent":25},
     *      ],
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     */
    /**
     * @param RiskChangeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompetitorRiskChange(RiskChangeRequest $request)
    {
        return $this->respondWithArray([
            'change_percent' => $request->user()->company->competitorReputationChangeBetweenDates(
                new Carbon($request->get('start_datetime')),
                new Carbon($request->get('end_datetime'))
            )
        ]);
    }

}
