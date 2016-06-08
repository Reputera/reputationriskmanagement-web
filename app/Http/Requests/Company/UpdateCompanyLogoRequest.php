<?php

namespace App\Http\Requests\Company;


use App\Http\Requests\ApiRequest;

class UpdateCompanyLogoRequest extends ApiRequest
{

    public function rules() {
        return [
            'companies_name' => 'required',
            'logo' => 'required|image|dimensions:max_width='.config('rrm.filesystem.logo.max_width').',max_height='.config('rrm.filesystem.logo.max_height'),
        ];
    }

}