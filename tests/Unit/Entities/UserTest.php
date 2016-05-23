<?php

namespace Tests\Unit\Entities;

use App\Entities\Exceptions\InvalidRoleAssignment;
use App\Entities\Role;
use App\Entities\Status;
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
    public function userIsToggledToEnabled()
    {
        $attributes = $this->getValidAttributes();
        $attributes['deleted_at'] = date('Y-m-d H:i:s');
        $attributes['status'] = Status::DISABLED;

        User::unguard(); // Normally you don't set the deleted at, and it's guarded, so we need to unguard to set it.
        $user = User::create($attributes);

        $user->toggleTrashed();

        $this->assertEquals(Status::ENABLED, $user->status);
        $this->seeIsNotSoftDeletedInDatabase('users', ['id' => $user->id, 'status' => $user->status]);
    }

    /** @test */
    public function userIsToggledToDisabled()
    {
        $attributes = $this->getValidAttributes();
        $attributes['status'] = Status::ENABLED;

        $user = User::create($attributes);

        $user->toggleTrashed();

        $this->assertEquals(Status::DISABLED, $user->status);
        $this->seeIsSoftDeletedInDatabase('users', ['id' => $user->id, 'status' => $user->status]);
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

    /** @test */
    public function userIsTheGivenRole()
    {
        $attributes = $this->getValidAttributes();
        $attributes['role'] = Role::USER;
        $user = User::create($attributes);

        $this->assertTrue($user->hasRole(Role::USER));

        $allRoles = Role::all();
        // Remove the role we have already tested.
        unset($allRoles['USER']);

        foreach ($allRoles as $role) {
            $this->assertFalse($user->hasRole($role));
        }
    }

    /** @test */
    public function userIsTheGivenStatus()
    {
        $attributes = $this->getValidAttributes();
        $attributes['status'] = Status::DISABLED;
        $user = User::create($attributes);

        $this->assertTrue($user->isOfStatus('DisABLed'));

        $allStatuses = Status::all();
        // Remove the status we have already tested.
        unset($allStatuses['DISABLED']);

        foreach ($allStatuses as $status) {
            $this->assertFalse($user->isOfStatus($status));
        }
    }

    protected function getValidAttributes()
    {
        return $attributes = [
            'name' => 'some name',
            'password' => bcrypt('password'),
            'email' => 'lol@lol.com',
            'role' => Role::ADMIN,
            'status' => Status::ENABLED
        ];
    }
}
