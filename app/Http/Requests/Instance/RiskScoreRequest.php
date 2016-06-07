<?php

namespace App\Http\Requests\Instance;

use App\Http\Requests\ApiRequest;

class RiskScoreRequest extends ApiRequest
{

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
