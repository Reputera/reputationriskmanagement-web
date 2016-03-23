<?php

namespace App\Http\Controllers\Instance;

use App\Entities\Instance;
use App\Http\Controllers\ApiController;
use App\Http\Pipelines\Query\SortingPipeline;
use App\Http\Requests\Instance\InstanceQueryRequest;
use App\Http\Requests\Instance\RiskScoreRequest;
use App\Http\Traits\PaginationTrait;
use App\Transformers\Instance\InstanceTransformer;

class QueryController extends ApiController
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInstances(InstanceQueryRequest $request)
    {
        $builder = Instance::select([
            'instances.*'
        ])
            ->selectRaw('(instances.positive_sentiment - instances.negative_sentiment) as risk_score')
            ->join('vectors', 'vectors.id', '=', 'instances.vector_id')
            ->join('companies', 'companies.id', '=', 'instances.company_id')
            ->leftJoin('instance_country', 'instances.id', '=', 'instance_country.instance_id')
            ->leftJoin('countries', 'countries.id', '=', 'instance_country.country_id')
            ->leftJoin('regions', 'regions.id', '=', 'countries.region_id');

        $builder = $request->sendBuilderThroughPipeline($builder, [SortingPipeline::class]);
        $builder->where($request->getForQuery([
            'vectors_name',
            'companies_name',
            'regions_name',
        ]));

        if ($start = $request->input('start_datetime')) {
            $builder->where('instances.start', '>', $start);
        }
        if ($end = $request->input('end_datetime')) {
            $builder->where('instances.start', '<', $end);
        }

        $resultCollection = $builder->get();
        return $this->respondWithArray([
            'total_sentiment_score' => ($resultCollection->sum('positive_sentiment') - $resultCollection->sum('negative_sentiment')) / $resultCollection->count() * 100,
            'instances' => $resultCollection->first()->id ? $this->fractalize($resultCollection, new InstanceTransformer()) : ['data'=>[]]
        ]);
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
