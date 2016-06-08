<?php

namespace App\Http\Requests\Instance;

use App\Http\Requests\ApiRequest;

class RiskScoreRequest extends ApiRequest
{

    /**
     * @apiDefine RiskScoreParams
     * @apiParam {String} company_name
     * @apiParam {Number=7,30,186,365} lastDays (Required if start_datetime not given)
     * @apiParam {String} start_datetime Acceptable format: YYYY-MM-DD HH:ii:ss (Required if lastDays not given)
     * @apiParam {String} end_datetime Acceptable format: YYYY-MM-DD HH:ii:ss (Required if lastDays not given)
     * @apiParam {Number} [vector] ID of vector
     */
    /**
     * @apiDefine RiskScoreQueryErrors
     * @apiErrorExample {json} Error-Response:
     *  HTTP/1.1 422 Unprocessable Entity
     *  {
     *      "message": "Invalid request",
     *      "status_code": 422,
     *      "errors":
     *      {
     *          "lastDays": [
     *              "The time frame is required.",
     *          ],
     *          "vector": [
     *              "The selected vector is invalid."
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
            'lastDays' => 'required_without:start_datetime|in:7,30,186,365',
            'vector' => 'exists:vectors,id',
            'start_datetime' => 'required_without:lastDays|date',
            'end_datetime' => 'required_without:lastDays|date'
        ];
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                'lastDays.required' => 'The time frame is required.',
            ]
        );
    }
}
