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
     *              "risk": "medium",
     *              "vectors":[
     *                  {
     *                      "vector1":"vector name",
     *                      "count":10,
     *                      "risk": "medium",
     *                      "percent_change": 10
     *                  }
     *                  {
     *                      "vector1":"vector name",
     *                      "count":5,
     *                      "risk": "medium"
     *                      "percent_change": 10
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
        $startTime = new Carbon($request->get('start_datetime'));
        $endTime = new Carbon($request->get('end_datetime'));

        $startTimeString = $startTime->toDateString() . '00:00:00';
        $endTimeString = $endTime->toDateString() . '23:59:59';

        $companyId = $request->user()->company_id;

        \DB::setFetchMode(\PDO::FETCH_ASSOC);
        $regionList = \DB::table('instances')
            ->selectRaw('regions.name as region')
            ->selectRaw('count(*) as count')
            ->selectRaw('CASE
        WHEN risk_score < -66 THEN \'high\'
        WHEN risk_score > 66 THEN \'low\'
        ELSE \'medium\'
    END AS risk')
            ->where('instances.company_id', $companyId)
            ->where('instances.start', '>', $startTimeString)
            ->where('instances.start', '<', $endTimeString)
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
                $startTime,
                $endTime
            );
            \DB::setFetchMode(\PDO::FETCH_ASSOC);
            $regionList[$key]['vectors'] = $this->getRegionVectorData(
                $region['region'],
                $startTimeString,
                $endTimeString,
                $companyId
            );
            \DB::setFetchMode(\PDO::FETCH_CLASS);
            foreach($regionList[$key]['vectors'] as $vectorKey => $vectorData) {
                $regionList[$key]['vectors'][$vectorKey]['percent_change'] = $request->user()->company->reputationChangeForRegionBetweenDates(
                    Region::where(['name' => $region['region']])->first(),
                    new Carbon($request->get('start_datetime')),
                    new Carbon($request->get('end_datetime')),
                    $regionList[$key]['vectors'][$vectorKey]['id']
                );
            }
        }
        return $this->respondWithArray($regionList);
    }

    protected function getRegionVectorData($region, $start, $end, $companyId) {
        return \DB::table('instances')
            ->selectRaw('vectors.id as id')
            ->selectRaw('vectors.name as vector')
            ->selectRaw('count(*) as count')
            ->selectRaw('CASE
                    WHEN risk_score < -66 THEN \'high\'
                    WHEN risk_score > 66 THEN \'low\'
                    ELSE \'medium\'
                END AS risk')
            ->join('vectors', 'vectors.id', '=', 'instances.vector_id')
            ->leftJoin('instance_country', 'instances.id', '=', 'instance_country.instance_id')
            ->leftJoin('countries', 'countries.id', '=', 'instance_country.country_id')
            ->leftJoin('regions', 'regions.id', '=', 'countries.region_id')
            ->whereNotNull('vectors.name')
            ->groupBy('vectors.name')
            ->orderBy('count', 'desc')
            ->where('instances.company_id', '=', $companyId)
            ->where('regions.name', '=', $region)
            ->where('instances.start', '>', $start)
            ->where('instances.start', '<', $end)
            ->get();
    }

}