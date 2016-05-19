<?php

namespace App\Http\Controllers\Admin\Users;

use App\Entities\Company;
use App\Entities\Role;
use App\Http\Controllers\Controller;

class UserUIController extends Controller
{
    public function get()
    {
        return view('admin.users.create', [
            'companies' => Company::all(),
            'userRoles' => [Role::ADMIN, Role::USER]
        ]);
    }
}
