<?php

namespace Tests\Unit\Entities;

use App\Entities\Exceptions\InvalidRoleAssignment;
use App\Entities\Role;
use App\Entities\User;

class UserTest extends \TestCase
{
    /** @test */
    public function creatingAUserWithoutAValidRole()
    {
        $this->setExpectedException(InvalidRoleAssignment::class);

        $attributes = $this->getValidAttributes();
        unset($attributes['role']);
        User::create($attributes);
    }

    /** @test */
    public function creatingAUserAValidRole()
    {
        $attributes = $this->getValidAttributes();
        User::create($attributes);

        $this->seeInDatabase('users', $attributes);
    }

    /** @test */
    public function firstOrCreateRequiresAValidRoleIfUserIsNotAlreadyInDatabase()
    {
        $this->setExpectedException(InvalidRoleAssignment::class);

        $attributes = $this->getValidAttributes();
        unset($attributes['role']);
        (new User)->firstOrCreate($attributes);
    }

    /** @test */
    public function userIsAnAdmin()
    {
        $attributes = $this->getValidAttributes();
        $attributes['role'] = Role::ADMIN;
        $user = User::create($attributes);

        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function userIsNotAnAdmin()
    {
        $attributes = $this->getValidAttributes();
        $attributes['role'] = Role::USER;
        $user = User::create($attributes);

        $this->assertFalse($user->isAdmin());
    }

    protected function getValidAttributes()
    {
        return $attributes = [
            'name' => 'some name',
            'password' => bcrypt('password'),
            'email' => 'lol@lol.com',
            'role' => Role::ADMIN
        ];
    }
}
