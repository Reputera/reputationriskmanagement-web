<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\ApiRequest;

class NewCompanyRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'companies' => 'required|array',
            'companies.*' => 'required|array',
            'companies.*.name' => 'required|max:255',
            'companies.*.entity_id' => 'required|unique:companies,entity_id|max:255',
            'companies.*.industry_id' => 'required|exists:industries,id',
        ];
    }
}
