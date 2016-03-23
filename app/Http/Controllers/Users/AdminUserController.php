<?php

namespace App\Http\Controllers\Users;

use App\Entities\Company;
use App\Entities\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\NewUserRequest;
use App\Http\Traits\CreatesUser;

class AdminUserController extends Controller
{
    use CreatesUser;

    public function get()
    {
        return view('adminUsers.create', [
            'companies' => Company::all(),
            'userRoles' => Role::all()
        ]);
    }

    public function store(NewUserRequest $request)
    {
        return $this->createUser($request->all(), $request->get('role'));
    }
}
