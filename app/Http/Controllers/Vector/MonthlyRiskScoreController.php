<?php

namespace App\Http\Controllers\Vector;

use App\Http\Controllers\Controller;
use App\Entities\Vector;
use App\Http\Requests\Instance\MonthlyVectorScoresRequest;
use Illuminate\Support\Facades\Auth;

class MonthlyRiskScoreController extends Controller
{
    /**
     * @api {get} /vector-risk-scores-by-month/ Per month
     * @apiName VectorRiskScorePerMonth
     * @apiDescription Retrieves risk score broken down for each year/month given for the customer's assigned company.
     * @apiGroup Vectors
     * @apiUse MonthlyVectorRiskScoresParams
     * @apiUse MonthlyVectorRiskScoresErrors
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data": [
     *          {
     *              "date":"2016-04",
     *              "vectors": [
     *                  {"vector":"Vector 1","value": 10},
     *                  {"vector":"Vector 2","value": 20},
     *                  {"vector":"Vector 3","value": "N/A"}
     *              ]
     *          },
     *          {
     *              "date":"2016-04",
     *              "vectors": [
     *                  {"vector":"Vector 1","value": 10},
     *                  {"vector":"Vector 2","value": 20},
     *                  {"vector":"Vector 3","value": "N/A"}
     *              ]
     *          },
    *          ...
     *      ],
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     */
    /**
     * @param MonthlyVectorScoresRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function byCompany(MonthlyVectorScoresRequest $request)
    {
        $company = Auth::user()->company;
        $vectorScores = [];

        foreach ($request->get('dates') as $date) {
            list($year, $month) = explode('-', $date);
            $dateData = [
                'date' => $year.'-'.$month,
            ];
            foreach ($vectors = Vector::all() as $vector) {
                $dateData['vectors'][] = [
                    'vector' => $vector->name,
                    'value' => $vector->riskScoreForCompanyByYearAndMonth(
                        $company,
                        $year,
                        $month
                    )
                ];
            }
            $vectorScores[] = $dateData;
        }

        return $this->respondWithArray($vectorScores);
    }
}
