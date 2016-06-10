<?php

namespace App\Http\Requests\InstanceAlert;


use App\Http\Requests\ApiRequest;

class GetAlertsRequest extends ApiRequest
{

    /**
     * @apiDefine GetAlerts
     * @apiParam {Boolean} [dismissed] Pass dismissed as true to include dismissed alerts in results.
     * @apiUse MultipleInstances
     * @apiExample {json} Example request:
     *  {
     *      dismissed: true
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
            'dismissed' => 'boolean',
        ];
    }

}