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
            'vector' => 'exists:vectors,name',
            'country' => 'exists:countries,name'
        ];
    }

}