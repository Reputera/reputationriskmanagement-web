<?php

namespace App\Transformers\User;

use App\Entities\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        $phoneNumber = null;
        if ($user->phone_number) {
            $phoneNumber = "(".substr($user->phone_number, 0, 3).") ".
                substr($user->phone_number, 3, 3)."-".substr($user->phone_number, 6);
        }

        return [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone_number' => $phoneNumber,
            'phone_number_extension' => $user->phone_number_extension
        ];
    }
}
