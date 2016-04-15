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
