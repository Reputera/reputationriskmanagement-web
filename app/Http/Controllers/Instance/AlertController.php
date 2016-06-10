<?php

namespace App\Http\Controllers\Instance;


use App\Http\Controllers\Controller;
use App\Http\Requests\InstanceAlert\GetAlertsRequest;
use App\Transformers\Instance\InstanceTransformer;

class AlertController extends Controller
{


    public function getAlertedInstances(GetAlertsRequest $apiRequest)
    {
        return $this->respondWith(
            $apiRequest->user()->getAlertedInstances(),
            new InstanceTransformer()
        );
    }

}