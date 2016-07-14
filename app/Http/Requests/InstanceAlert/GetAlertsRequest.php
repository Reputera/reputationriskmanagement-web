<?php

namespace App\Http\Requests\InstanceAlert;


use App\Http\Requests\ApiRequest;

class GetAlertsRequest extends ApiRequest
{

    /**
     * @apiDefine GetAlertsParams
     * @apiParam {Boolean} [dismissed] Pass dismissed as true to include dismissed alerts in results.
     * @apiParam {String} [start_datetime] Acceptable format: YYYY-MM-DD HH:ii:ss (2016-06-07 17:54:15)
     * @apiParam {String} [end_datetime] Acceptable format: YYYY-MM-DD HH:ii:ss (2016-06-07 17:54:15)
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
            'dismissed' => 'boolean',
        ];
    }

}