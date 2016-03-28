<?php

namespace Tests\Features\Login\Web\Admin;

use App\Entities\Role;
use App\Entities\User;

class UserCanLoginTest extends \TestCase
{
    public function testAdminUserCanLoginWithValidCredentials()
    {
        $password = 'password';
        $user = factory(User::class)->create([
            'password' => bcrypt($password),
            'role' => Role::ADMIN
        ]);

        $this->visit('/login')
            ->type($user->email, 'email')
            ->type($password, 'password')
            ->press('Sign In')
            ->seePageIs('/')
            ->see($user->name);
    }
}
