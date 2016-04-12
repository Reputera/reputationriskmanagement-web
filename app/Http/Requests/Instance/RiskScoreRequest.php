<?php

namespace App\Http\Requests\Instance;

use App\Http\Requests\Request;

class RiskScoreRequest extends Request
{
    /**
     * @apiDefine RiskScoreParams
     * @apiParam {String} company_name
     * @apiParam {Number=7,30,186,365} lastDays
     * @apiParam {Number} [vector]
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
     *          "company_name": [
     *              "The company name field is required."
     *          ],
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
            'company_name' => 'required',
            'lastDays' => 'required|in:7,30,186,365',
            'vector' => 'exists:vectors,id',
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
