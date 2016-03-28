<?php

namespace Features\Login\Web\NonAdminUsers;

use App\Entities\Role;
use App\Entities\User;

class OnlyAdminUsersCanLoginTest extends \TestCase
{
    public function testUserMustBeAdminToLogin()
    {
        $password = 'password';
        $user = factory(User::class)->create([
            'password' => bcrypt($password),
            'role' => Role::USER
        ]);

        $this->visit('/login')
            ->type($user->email, 'email')
            ->type($password, 'password')
            ->press('Sign In')
            ->seePageIs('/login')
            ->see('These credentials do not match our records.');

        $user = factory(User::class)->create([
            'password' => bcrypt($password),
            'role' => Role::USER_ACCOUNT_ADMIN
        ]);

        $this->visit('/login')
            ->type($user->email, 'email')
            ->type($password, 'password')
            ->press('Sign In')
            ->seePageIs('/login')
            ->see('These credentials do not match our records.');
    }
}
