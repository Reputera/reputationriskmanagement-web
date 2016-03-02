<?php

namespace App\Entities;

use App\Services\Traits\Enumerable;

class Role
{
    use Enumerable;

    const ADMIN = 'Admin';
    const USER = 'User';
}
