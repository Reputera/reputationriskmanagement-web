<?php

namespace App\Http\Requests\Industry;

use App\Http\Requests\ApiRequest;

class NewIndustryRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'industry_name' => 'required|string|max:255',
        ];
    }
}
