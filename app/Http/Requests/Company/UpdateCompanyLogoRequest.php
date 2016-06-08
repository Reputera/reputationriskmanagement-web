<?php

namespace App\Http\Requests\Company;


use App\Http\Requests\ApiRequest;

class UpdateCompanyLogoRequest extends ApiRequest
{

    public function rules() {
        return [
            'company_id' => 'required',
            'logoImage' => 'required|image|dimensions:max_width='.config('rrm.filesystem.logo.max_width').',max_height='.config('rrm.filesystem.logo.max_height'),
        ];
    }

    public function messages()
    {
        return [
            'logoImage.dimensions' => 'The provided image must have a resolution of ' . config('rrm.filesystem.logo.max_width') . 'px x' . config('rrm.filesystem.logo.max_height') . 'px',
        ];
    }

}