<?php

namespace App\Transformers\User;

use App\Entities\User;
use Illuminate\Support\Facades\Auth;
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

        $returnArray = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone_number' => $phoneNumber,
            'phone_number_extension' => $user->phone_number_extension
        ];

        if (Auth::check() && Auth::user()->isAdmin()) {
            $returnArray['id'] = $user->id;
            $returnArray['updated_at'] = (string) $user->updated_at;
            $returnArray['deleted_at'] = (string) $user->deleted_at;
        }

        return $returnArray;
    }
}
