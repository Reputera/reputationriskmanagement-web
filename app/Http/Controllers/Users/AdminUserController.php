<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\NewUserRequest;
use App\Http\Traits\CreatesUser;

class AdminUserController extends Controller
{
    use CreatesUser;

    public function store(NewUserRequest $request)
    {
        return $this->createUser($request->all(), $request->get('role'));
    }
}
