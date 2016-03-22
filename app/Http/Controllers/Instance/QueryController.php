<?php

namespace App\Http\Controllers\Instance;


use App\Entities\Instance;
use App\Http\Controllers\Controller;
use App\Http\Pipelines\Query\SortingPipeline;
use App\Http\Traits\PaginationTrait;
use App\Transformers\Instance\InstanceTransformer;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    use PaginationTrait;

    public function getInstances(Request $request)
    {
        $pipeline = app('Illuminate\Pipeline\Pipeline');
        $pipeline->send($request);
        $pipeline->through([SortingPipeline::class]);
        $builder = Instance::query();
        $builder = $pipeline->then(function ($request) use ($builder) {
            return $builder;
        });
        return $this->respondWith($builder->get(), new InstanceTransformer());
    }
}