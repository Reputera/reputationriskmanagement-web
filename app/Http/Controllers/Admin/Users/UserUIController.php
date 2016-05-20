<?php

namespace App\Http\Controllers\Admin\Users;

use App\Entities\Company;
use App\Entities\Role;
use App\Entities\User;
use App\Http\Controllers\Controller;
use App\Transformers\User\UserTransformer;

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

    public function listAll()
    {
        \JavaScript::put([
            'users' => $this->fractalizeCollection(User::all(), new UserTransformer())['data']
        ]);

        return view('admin.users.list');
    }
}
