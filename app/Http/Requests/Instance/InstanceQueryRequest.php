<?php

namespace App\Http\Requests\Instance;


use App\Http\Requests\Request;

class InstanceQueryRequest extends Request
{

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
            'vectors_name' => 'exists:vectors,name',
            'regions_name' => 'exists:regions,name',
            'companies_name' => 'required'
        ];
    }

}