<?php

namespace App\Http\Controllers\Users;

use App\Entities\Role;
use App\Http\Requests\Users\NewUserRequest;
use App\Http\Traits\CreatesUser;

class UserController extends Controller
{
    use CreatesUser;

    public function store(NewUserRequest $request)
    {
        return $this->createUser($request->all(), Role::USER);
    }
}
