<?php

namespace App\Http\Requests\Company;


use App\Http\Requests\ApiRequest;

class UpdateCompanyRequest extends ApiRequest
{

    public function rules() {
        return [
            'max_alert_threshold' => 'numeric: between:0,100',
            'min_alert_threshold' => 'numeric: between:-100,0',
        ];
    }

}