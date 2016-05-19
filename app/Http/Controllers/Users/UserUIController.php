<?php

namespace App\Http\Controllers\Users;

use App\Entities\Company;
use App\Entities\Role;
use App\Http\Controllers\Controller;

class UserUIController extends Controller
{
    public function get()
    {
        return view('adminUsers.create', [
            'companies' => Company::all(),
            'userRoles' => [Role::ADMIN, Role::USER]
        ]);
    }
}
