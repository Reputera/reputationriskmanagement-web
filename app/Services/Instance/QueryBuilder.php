<?php

namespace App\Services\Instance;


use App\Entities\Instance;
use App\Http\Pipelines\Query\SortingPipeline;
use App\Http\Requests\Request;

class QueryBuilder
{
    public function queryInstances(Request $request, array $paramArray = [])
    {
        $builder = Instance::select([
            'instances.*'
        ])
            ->join('vectors', 'vectors.id', '=', 'instances.vector_id')
            ->join('companies', 'companies.id', '=', 'instances.company_id')
            ->leftJoin('instance_country', 'instances.id', '=', 'instance_country.instance_id')
            ->leftJoin('countries', 'countries.id', '=', 'instance_country.country_id')
            ->leftJoin('regions', 'regions.id', '=', 'countries.region_id')
            ->groupBy('instances.id');

        $builder = $request->sendBuilderThroughPipeline($builder, [SortingPipeline::class]);
        $builder->where(array_only($paramArray, ['vectors.name', 'regions.name']));

        if(array_get($paramArray, 'hideFlagged')) {
            $builder->where('flagged', null);
        }

        if (array_has($paramArray, 'companies.name')) {
            $builder->whereIn('companies.name', explode(',', $paramArray['companies.name']));
        }

        if ($start = $request->input('start_datetime')) {
            $builder->where('instances.start', '>', $start);
        }
        if ($end = $request->input('end_datetime')) {
            $builder->where('instances.start', '<', $end);
        }
        return $builder;
    }
}
