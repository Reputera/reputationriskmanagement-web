<?php

namespace App\Http\Requests\Instance;


use App\Http\Requests\Request;

class RiskScoreRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date',
            'company_id' => 'required'
        ];
    }

}