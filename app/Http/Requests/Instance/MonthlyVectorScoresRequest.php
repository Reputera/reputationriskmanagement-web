<?php

namespace App\Http\Requests\Instance;

use App\Http\Requests\Request;

class MonthlyVectorScoresRequest extends Request
{
    /**
     * @apiDefine MonthlyVectorRiskScoresParams
     * @apiParam {Array} dates Acceptable formats: 2015-04, 2015-4
     */
    /**
     * @apiDefine MonthlyVectorRiskScoresErrors
     * @apiErrorExample {json} Error-Response:
     *  HTTP/1.1 422 Unprocessable Entity
     *  {
     *      "message": "Invalid request",
     *      "status_code": 422,
     *      "errors":
     *      {
     *          "dates": [
     *              "The dates field is required."
     *          ],
     *          "dates.0": [ // Note the 0 will be the key of the failing array value.
     *              "The expected format is YYYY-MM."
     *          ]
     *      }
     *  }
     */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dates' => 'required|array',
            'dates.*' => 'regex:/^[0-9]{4}-[0-9]{1,2}$/',
        ];
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                'dates.*.regex' => 'The expected format is YYYY-MM'
            ]
        );
    }
}
