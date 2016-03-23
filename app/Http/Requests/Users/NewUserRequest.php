<?php

namespace App\Http\Requests\Users;

use App\Entities\Role;
use App\Http\Requests\Request;

class NewUserRequest extends Request
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
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:'.implode(',', Role::values()),
            'company_id' => 'required|exists:companies,id'
        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => 'The company field is required.'
        ];
    }
}
