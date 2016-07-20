<?php

namespace App\Services\Instance;

use App\Entities\Instance;
use App\Http\Pipelines\Query\SortingPipeline;
use App\Http\Requests\Request;
use Carbon\Carbon;

class QueryBuilder
{
    public function queryInstances(Request $request, array $paramArray = [])
    {
        $builder = Instance::select([
            'instances.*'
        ])
            ->leftJoin('vectors', 'vectors.id', '=', 'instances.vector_id')
            ->join('companies', 'companies.id', '=', 'instances.company_id')
            ->leftJoin('instance_country', 'instances.id', '=', 'instance_country.instance_id')
            ->leftJoin('countries', 'countries.id', '=', 'instance_country.country_id')
            ->leftJoin('regions', 'regions.id', '=', 'countries.region_id')
            ->groupBy('instances.id');

        $builder = $request->sendBuilderThroughPipeline($builder, [SortingPipeline::class]);
        $builder->where(array_only($paramArray, ['vectors.name', 'regions.name']));

        if ($request->get('showDeleted')) {
            $builder->withTrashed();
        }

        if (array_has($paramArray, 'fragment')) {
            $builder->where('fragment', 'LIKE', '%' . $paramArray['fragment'] . '%');
        }

        if (array_has($paramArray, 'companies.name')) {
            $builder->whereIn('companies.name', explode(',', $paramArray['companies.name']));
        }

        if ($start = $request->input('start_datetime')) {
            $builder->where('instances.start', '>=', (new Carbon($start))->toDateString().' 00:00:00');
        }
        if ($end = $request->input('end_datetime')) {
            $builder->where('instances.start', '<=', (new Carbon($end))->toDateString().' 23:59:59');
        }
        $builder->orderBy('instances.start', 'desc');
        return $builder;
    }
}
