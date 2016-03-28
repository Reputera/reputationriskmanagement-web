<?php

namespace Tests\Features\Login\Web\Admin;

use App\Entities\Role;
use App\Entities\User;

class CannotLoginWithoutProperCredentialsTest extends \TestCase
{
    public function testInvalidCredentialsWillNotAllowLogin()
    {
        $password = 'password';
        $user = factory(User::class)->create([
            'password' => bcrypt($password),
            'role' => Role::ADMIN
        ]);

        $this->visit('/login')
            ->type('random@email.com', 'email')
            ->type($password, 'password')
            ->press('Sign In')
            ->seePageIs('/login')
            ->see('These credentials do not match our records.');

        $this->visit('/login')
            ->type($user->email, 'email')
            ->type('incorrectPassword', 'password')
            ->press('Sign In')
            ->seePageIs('/login')
            ->see('These credentials do not match our records.');
    }
}
