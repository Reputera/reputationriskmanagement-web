<?php

namespace App\Http\Controllers\Instance;


use App\Entities\Instance;
use App\Http\Controllers\Controller;
use App\Http\Pipelines\Query\SortingPipeline;
use App\Http\Requests\Instance\InstanceQueryRequest;
use App\Http\Traits\PaginationTrait;
use App\Transformers\Instance\InstanceTransformer;

class QueryController extends Controller
{
    use PaginationTrait;

    /**
     * @api {get} /instances/ List instances
     * @apiName ListInstances
     * @apiDescription List instances based on query parameters. This endpoint is pagination enabled.
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
            ->leftJoin('instance_country', 'instances.id', '=', 'instance_country.instance_id')
            ->leftJoin('countries', 'countries.id', '=', 'instance_country.country_id');

        $builder = $request->sendBuilderThroughPipeline($builder, [SortingPipeline::class]);
        $builder->where($request->onlyArray([
            'vectors.name',
            'company.name',
            'country.name'
        ]));

        if($start = $request->input('start_datetime')) {
            $builder->where('instances.created_at', '>', $start);
        }
        if($end = $request->input('end_datetime')) {
            $builder->where('instances.created_at', '<', $end);
        }

        return $this->respondWith($builder->get(), new InstanceTransformer());
    }
}