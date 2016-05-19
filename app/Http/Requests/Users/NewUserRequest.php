<?php

namespace App\Http\Requests\Users;

use App\Entities\Role;
use App\Http\Requests\ApiRequest;

class NewUserRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'role' => 'required|in:'.implode(',', Role::values()),
            'phone_number' => 'regex:/^[0-9]{10}$/|required_unless:role,'.Role::ADMIN,
            'phone_number_extension' => 'regex:/^[0-9]{0,10}$/',
            'password' => 'required|min:6',
            'company_id' => 'exists:companies,id|required_unless:role,'.Role::ADMIN
        ];
    }

    public function messages()
    {
        return [
            'company_id.required_unless' => 'The company field is required.',
            'phone_number.required_unless' => 'The phone number field is required.',
            'phone_number.regex' => 'The phone number must be 10 numbers only.',
            'phone_number_extension.regex' => 'The phone number extension must be a maximum of 10 numbers only.'
        ];
    }
}
