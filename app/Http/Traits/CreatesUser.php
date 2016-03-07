<?php

namespace App\Http\Traits;

use App\Entities\User;

trait CreatesUser
{
    protected function createUser(array $data, string $role)
    {
        return User::create([
            'name' => array_get($data, 'name'),
            'email' => array_get($data, 'email'),
            'password' => array_get($data, 'password'),
            'role' => $role
        ]);
    }
}
