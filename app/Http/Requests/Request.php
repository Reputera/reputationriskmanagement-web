<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    /**
     * @return \App\Entities\User
     */
    public function user($guard = null)
    {
        return parent::user();
    }
}
