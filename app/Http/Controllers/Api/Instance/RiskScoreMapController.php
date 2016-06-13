<?php

namespace App\Http\Controllers\Api\Instance;


use App\Entities\Region;
use App\Http\Controllers\ApiController;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RiskScoreMapController extends ApiController
{

    /**
     * @api {post} /riskScoreMapData Risk Score map data
     * @apiName RiskScoreMapData
     * @apiDescription Return the risk score data for risk map
     * @apiUse RiskScoreMapDataParams
     * @apiGroup Instances
     * @apiExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data":[
     *          {
     *              "region":"Region name",
     *              "count":15,
     *              "percent_change":20,
     *              "vectors":[
     *                  {
     *                      "vector1":"vector name",
     *                      "count":10
     *                  }
     *                  {
     *                      "vector1":"vector name",
     *                      "count":5
     *                  }
     *              ]
     *          },
     *      ],
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     */
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRiskScoreMapData(Request $request) {
        \DB::setFetchMode(\PDO::FETCH_ASSOC);
        $regionList = \DB::table('instances')
            ->selectRaw('regions.name as region')
            ->selectRaw('count(*) as count')
            ->where('instances.company_id', $request->user()->company_id)
            ->where('instances.start', '>', $request->input('start_datetime'))
            ->where('instances.start', '<', $request->input('end_datetime'))
            ->whereNotNull('regions.name')
            ->join('vectors', 'vectors.id', '=', 'instances.vector_id')
            ->leftJoin('instance_country', 'instances.id', '=', 'instance_country.instance_id')
            ->leftJoin('countries', 'countries.id', '=', 'instance_country.country_id')
            ->leftJoin('regions', 'regions.id', '=', 'countries.region_id')
            ->groupBy('regions.name')
            ->orderBy('count', 'desc')
            ->get();
        \DB::setFetchMode(\PDO::FETCH_CLASS);
        foreach($regionList as $key => $region) {
            $regionList[$key]['percent_change'] = $request->user()->company->reputationChangeForRegionBetweenDates(
                Region::where(['name' => $region['region']])->first(),
                new Carbon($request->get('start_datetime')),
                new Carbon($request->get('end_datetime'))
            );
            $regionList[$key]['vectors'] = $this->getRegionVectorData(
                $region['region'],
                $request->input('start_datetime'),
                $request->input('end_datetime')
            );
        }

        return $this->respondWithArray($regionList);
    }

    protected function getRegionVectorData($region, $start, $end) {
        return \DB::table('instances')
            ->selectRaw('vectors.name as vector')
            ->selectRaw('count(*) as count')
            ->join('vectors', 'vectors.id', '=', 'instances.vector_id')
            ->leftJoin('instance_country', 'instances.id', '=', 'instance_country.instance_id')
            ->leftJoin('countries', 'countries.id', '=', 'instance_country.country_id')
            ->leftJoin('regions', 'regions.id', '=', 'countries.region_id')
            ->whereNotNull('vectors.name')
            ->groupBy('vectors.name')
            ->orderBy('count', 'desc')
            ->where('regions.name', '=', $region)
            ->where('instances.start', '>', $start)
            ->where('instances.start', '<', $end)
            ->get();
    }

}