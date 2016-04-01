<?php

namespace App\Http\Controllers\Instance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instance\InstanceQueryRequest;
use App\Http\Requests\Instance\RiskScoreRequest;
use App\Http\Requests\Request;
use App\Services\Instance\QueryBuilder;
use App\Transformers\Instance\InstanceTransformer;
use League\Csv\Writer;
use App\Http\Traits\PaginationTrait;

class QueryController extends Controller
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
     * @param InstanceQueryRequest $request
     * @param QueryBuilder $queryBuilder
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInstances(InstanceQueryRequest $request, QueryBuilder $queryBuilder)
    {
        $resultCollection = $this->paginateBuilder($queryBuilder->queryInstances($request, $request->getForQuery([
            'vectors_name', 'companies_name', 'regions_name', 'hideFlagged', 'fragment'
            ]))->with('countries.region'), $request
        );
        return $this->respondWithArray([
            'count' => $resultCollection->total(),
            'total_sentiment_score' => $resultCollection->total() ? (int)($resultCollection->sum('sentiment') / $resultCollection->total() * 100) : 0,
            'instances' => $this->fractalPaginate($resultCollection, new InstanceTransformer())
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
        $instances = $this->fractalize($resultCollection, new InstanceTransformer());
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(array_keys(array_get($instances, 'data.0')));
        $csv->insertAll($instances['data']);
        $csv->output($request->get('companies_name') . '.csv');
        die;
    }

    /**
     * @api {get} /riskScore/ Return the risk score for a company within a datetime range
     * @apiName RiskScore
     * @apiDescription Return the risk score for a company within a datetime range
     * @apiGroup Instances
     */
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
            'risk_score' => $resultCount ? (int)($resultCollection->sum('risk_score') / $resultCount) : 0
        ]);
    }
}
