<?php

namespace App\Http\Controllers\Users;

use App\Entities\User;
use App\Http\Requests\Users\NewUserRequest;
use App\Http\Controllers\ApiController;
use App\Transformers\User\UserTransformer;

class UserController extends ApiController
{
    public function store(NewUserRequest $request)
    {
        $createdUser = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone_number' => $request->get('phone_number'),
            'phone_number_extension' => $request->get('phone_number_extension'),
            'password' => bcrypt($request->get('password')),
            'role' => $request->get('role'),
        ]);

        if (!$createdUser->isAdmin()) {
            $createdUser->company_id = $request->get('company_id');
            $createdUser->save();
        }

        return $this->respondWithItem($createdUser, new UserTransformer());
    }
}
