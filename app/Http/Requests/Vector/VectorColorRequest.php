<?php

namespace App\Http\Requests\Vector;


use App\Http\Requests\ApiRequest;

class VectorColorRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'color' => 'required|regex:^#([A-Fa-f0-9]{6})$^',
        ];
    }

    public function messages()
    {
        return [
            'color.regex' => 'The color must be in hex format. (eg. #000000)'
        ];
    }

}