<?php

namespace App\Http\Controllers\Admin\Users;

use App\Entities\Status;
use App\Entities\User;
use App\Http\Requests\Request;
use App\Http\Requests\Users\NewUserRequest;
use App\Http\Controllers\ApiController;
use App\Transformers\User\UserTransformer;

class UserController extends ApiController
{
    public function createUser(NewUserRequest $request)
    {
        $createdUser = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone_number' => $request->get('phone_number'),
            'phone_number_extension' => $request->get('phone_number_extension'),
            'password' => bcrypt($request->get('password')),
            'role' => $request->get('role'),
            'status' => Status::EMAIL_NOT_CHANGED
        ]);

        if (!$createdUser->isAdmin()) {
            $createdUser->company_id = $request->get('company_id');
            $createdUser->save();
        }

        return $this->respondWithItem($createdUser, new UserTransformer());
    }

    public function toggle(Request $request)
    {
        $user = User::withTrashed()
            ->findOrFail($request->get('id'));

        if ($request->user()->id == $user->id) {
            return $this->errorResponse('You cannot enable/disable yourself.');
        }

        $user->toggleTrashed();

        return $this->respondWithItem($user, new UserTransformer);
    }
}
