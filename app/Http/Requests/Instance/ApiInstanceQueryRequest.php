<?php

namespace App\Http\Requests\Instance;

use App\Http\Requests\Request;

class ApiInstanceQueryRequest extends Request
{
    /**
     * @apiDefine InstanceQuery
     * @apiParam {String} start_datetime Acceptable format: YYYY-MM-DD HH:ii:ss
     * @apiParam {String} end_datetime Acceptable format: YYYY-MM-DD HH:ii:ss
     * @apiParam {String} vectors_name
     * @apiParam {String} regions_name
     * @apiExample {json} Example request:
     *  {
     *      start_datetime: "2015-03-1 00:00:00",
     *      end_datetime: "2015-5-1 00:00:00",
     *      vectors_name: "Vector",
     *      regions_name: "Region"
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
            'start_datetime' => 'date',
            'end_datetime' => 'date',
            'vectors_name' => 'exists:vectors,name',
            'regions_name' => 'exists:regions,name',
        ];
    }
}
