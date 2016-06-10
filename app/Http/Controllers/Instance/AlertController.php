<?php

namespace App\Http\Controllers\Instance;


use App\Http\Controllers\Controller;
use App\Http\Requests\InstanceAlert\GetAlertsRequest;
use App\Transformers\Instance\InstanceTransformer;

class AlertController extends Controller
{

    /**
     * @api {get} /instance/alerts Get Alerts
     * @apiUse GetAlertsParams
     * @apiName GetAlerts
     * @apiDescription Get the current user's alerts.
     * @apiGroup Alerts
     */
    /**
     * @param GetAlertsRequest $apiRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAlertedInstances(GetAlertsRequest $apiRequest)
    {
        return $this->respondWith(
            $apiRequest->user()->getAlertedInstances(),
            new InstanceTransformer()
        );
    }

    /**
     * @api {post} /instance/alerts/:instanceId Dismiss Alert
     * @apiName DismissAlert
     * @apiDescription Dismiss an alert by passing in an alerted instance's ID.
     * @apiGroup Alerts
     */
    /**
     * @param $instanceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function dismissAlert($instanceId)
    {
        auth()->user()->dismissAlert($instanceId);
        return $this->respondWithArray([]);
    }

}