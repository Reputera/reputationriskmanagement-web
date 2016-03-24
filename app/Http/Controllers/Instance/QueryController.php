<?php

namespace App\Http\Controllers\Instance;


use App\Entities\Instance;
use App\Http\Controllers\Controller;
use App\Http\Pipelines\Query\SortingPipeline;
use App\Http\Requests\Instance\InstanceQueryRequest;
use App\Http\Requests\Instance\RiskScoreRequest;
use App\Services\Instance\QueryBuilder;
use App\Transformers\Instance\InstanceTransformer;
use League\Csv\Writer;

class QueryController extends Controller
{

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInstances(InstanceQueryRequest $request, QueryBuilder $queryBuilder)
    {
        $resultCollection = $queryBuilder->queryInstances($request)
            ->with('countries.region')
            ->get();
        $resultCount = $resultCollection->count();
        return $this->respondWithArray([
            'count' => $resultCount,
            'total_sentiment_score' => $resultCount ? (int)((($resultCollection->sum('positive_sentiment') - $resultCollection->sum('negative_sentiment')) / $resultCount * 100)) : 0,
            'instances' => $this->fractalize($resultCollection, new InstanceTransformer())
        ]);
    }

    public function getInstancesCsv(InstanceQueryRequest $request, QueryBuilder $queryBuilder)
    {
        $resultCollection = $queryBuilder->queryInstances($request)->get();
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
     * @param RiskScoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRiskScore(RiskScoreRequest $request)
    {
        $riskScore = \DB::table('instances')
            ->selectRaw('((sum(positive_sentiment) - sum(negative_sentiment)) / count(*)) * 100 as risk_score')
            ->where('instances.start', '>', $request->input('start_datetime'))
            ->where('instances.start', '<', $request->input('end_datetime'))
            ->where('company_id', '=', $request->input('company_id'))
            ->first();
        return $this->respondWithArray(['risk_score' => $riskScore->risk_score]);
    }
}