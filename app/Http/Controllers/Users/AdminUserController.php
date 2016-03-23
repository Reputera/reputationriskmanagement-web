<?php

namespace App\Http\Controllers\Users;

use App\Entities\Company;
use App\Entities\Role;
use App\Entities\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\NewUserRequest;

class AdminUserController extends Controller
{
    public function get()
    {
        return view('adminUsers.create', [
            'companies' => Company::all(),
            'userRoles' => Role::all()
        ]);
    }

    public function store(NewUserRequest $request)
    {
        $createdUser = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'role' => $request->get('role'),
        ]);

        if (!$createdUser->isAdmin()) {
            $createdUser->company_id = $request->get('company_id');
            $createdUser->save();
        }

        return redirect()->route('adminUser.get');
    }
}
