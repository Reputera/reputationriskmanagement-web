<?php

namespace App\Http\Requests\Instance;

use App\Http\Requests\ApiRequest;

class RiskChangeRequest extends ApiRequest
{

    /**
     * @apiDefine RiskChangeParams
     * @apiParam {String} start_datetime Acceptable format: YYYY-MM-DD HH:ii:ss (Required if lastDays not given)
     * @apiParam {String} end_datetime Acceptable format: YYYY-MM-DD HH:ii:ss (Required if lastDays not given)
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
     *          "start_datetime": [
     *              "The start_datetime field is required.",
     *          ],
     *          "start_datetime": [
     *              "The end_datetime field is invalid."
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
            'start_datetime' => 'required_without:lastDays|date',
            'end_datetime' => 'required_without:lastDays|date'
        ];
    }
}
