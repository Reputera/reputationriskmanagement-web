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
     *      "data": {
     *          "2016-04": {
     *              "Vector 1": 10,
     *              "Vector 2": 20,
     *              "Vector 3": 30,
     *          },
     *          "2016-7": {
     *              "Vector 1": 20,
     *              "Vector 2": 30,
     *              "Vector 3": 10,
     *          },
     *          ...
     *      },
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
            foreach ($vectors = Vector::all() as $vector) {
                $vectorScores[$year.'-'.$month][$vector->name] = $vector->riskScoreForCompanyByYearAndMonth(
                    $company,
                    $year,
                    $month
                );
            }
        }

        return $this->respondWithArray($vectorScores);
    }
}
