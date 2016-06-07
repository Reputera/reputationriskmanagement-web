<?php

namespace App\Http\Requests\Instance;

use App\Http\Requests\Request;

class ApiInstanceQueryRequest extends Request
{
    /**
     * @apiDefine InstanceQuery
     * @apiParam {String} start_datetime Acceptable format: YYYY-MM-DD HH:ii:ss (2016-06-07 17:54:15)
     * @apiParam {String} end_datetime Acceptable format: YYYY-MM-DD HH:ii:ss (2016-06-07 17:54:15)
     * @apiParam {String} [vectors_name] Name of vector to get instances for.
     * @apiParam {String} [regions_name] Name of region to get instances for.
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
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date',
            'vectors_name' => 'exists:vectors,name',
            'regions_name' => 'exists:regions,name',
        ];
    }
}
