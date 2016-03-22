<?php

namespace App\Http\Controllers\Instance;


use App\Entities\Instance;
use App\Http\Controllers\Controller;
use App\Http\Pipelines\Query\SortingPipeline;
use App\Http\Requests\Request;
use App\Http\Traits\PaginationTrait;
use App\Transformers\Instance\InstanceTransformer;

class QueryController extends Controller
{
    use PaginationTrait;

    /**
     * @api {get} /instances/ List exams
     * @apiName ListInstances
     * @apiDescription List instances based on query parameters. This endpoint is pagination enabled.
     * @apiGroup Instances
     * @apiUse MultipleInstances
     * @apiUse PaginatedResults
     */
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInstances(Request $request)
    {
        $builder = $request->sendBuilderThroughPipeline(Instance::query(), [SortingPipeline::class]);
        return $this->respondWith($builder->get(), new InstanceTransformer());
    }
}