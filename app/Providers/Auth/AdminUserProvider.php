<?php

namespace App\Providers\Auth;

use App\Entities\Role;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Str;

class AdminUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (! Str::contains($key, 'password')) {
                $query->where($key, $value);
            }
        }
        $query->where('role', Role::ADMIN);

        return $query->first();
    }
}
