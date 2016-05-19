<?php

namespace App\Http\Controllers\Admin\Users;

use App\Entities\Company;
use App\Entities\Role;
use App\Http\Controllers\Controller;

class UserUIController extends Controller
{
    public function createUser()
    {
        \JavaScript::put([
            'companies' => Company::orderBy('name', 'ASC')->get(['id', 'name']),
            'roles' => Role::all()
        ]);

        return view('admin.users.create');
    }
}
