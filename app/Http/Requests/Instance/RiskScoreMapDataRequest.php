<?php

namespace App\Http\Requests\Instance;

use App\Http\Requests\Request;

class SentimentMapDataRequest extends Request
{
    /**
     * @apiDefine RiskScoreMapDataParams
     * @apiParam {String} start_datetime Acceptable format: YYYY-MM-DD HH:ii:ss
     * @apiParam {String} end_datetime Acceptable format: YYYY-MM-DD HH:ii:ss
     * @apiExample {json} Example request:
     *  {
     *      start_datetime: "2015-03-1 00:00:00",
     *      end_datetime: "2015-5-1 00:00:00"
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
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date',
        ];
    }
}
