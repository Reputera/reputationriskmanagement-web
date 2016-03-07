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
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:'.implode(',', Role::values())
        ];
    }

    public function messages()
    {
        if (!$this->user()->isAdmin()) {
            return [
                'role.required' => 'There was a problem with the server, please try again.',
                'role.in' => 'There was a problem with the server, please try again.',
            ];
        } else {
            return [];
        }
    }
}
