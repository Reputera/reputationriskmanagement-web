<?php

namespace App\Http\Controllers\Api\Instance;


use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class SentimentMapController extends ApiController
{

    public function getSentimentMapData(Request $request) {
        $query = \DB::table('instances')
            ->selectRaw('regions.name as region')
            ->selectRaw('vectors.name as vector')
            ->selectRaw('count(*) as count')
            ->where('instances.company_id', $request->user()->company_id)
            ->whereNotNull('regions.name')
            ->join('vectors', 'vectors.id', '=', 'instances.vector_id')
            ->leftJoin('instance_country', 'instances.id', '=', 'instance_country.instance_id')
            ->leftJoin('countries', 'countries.id', '=', 'instance_country.country_id')
            ->leftJoin('regions', 'regions.id', '=', 'countries.region_id')
            ->groupBy('regions.name');

        if ($start = $request->input('start_datetime')) {
            $query->where('instances.start', '>', $start);
        }
        if ($end = $request->input('end_datetime')) {
            $query->where('instances.start', '<', $end);
        }

        return $this->respondWithArray($query->get());
    }

    public function getRegionVectorData(Request $request) {
        $query = \DB::table('instances')
            ->selectRaw('vectors.name as vector')
            ->selectRaw('count(*) as count')
            ->join('vectors', 'vectors.id', '=', 'instances.vector_id')
            ->leftJoin('instance_country', 'instances.id', '=', 'instance_country.instance_id')
            ->leftJoin('countries', 'countries.id', '=', 'instance_country.country_id')
            ->leftJoin('regions', 'regions.id', '=', 'countries.region_id')
            ->groupBy('vectors.name')
            ->where('regions.name', '=', $request->input('region'));

        if ($start = $request->input('start_datetime')) {
            $query->where('instances.start', '>', $start);
        }

        if ($end = $request->input('end_datetime')) {
            $query->where('instances.start', '<', $end);
        }

        return $this->respondWithArray($query->get());
    }

}