<?php

namespace Features\User\Admins\EnableAndDisableUsers;

use App\Entities\Status;
use App\Entities\User;

class AdminCanToggleAUserEnabledAndDisabledTest extends \TestCase
{
    public function test_admin_can_enable_user_that_is_disabled()
    {
        $user = factory(User::class)->create(['status' => Status::DISABLED, 'deleted_at' => date('Y-m-d H:i:s')]);
        $this->beLoggedInAsAdmin();

        $this->post('users/toggle', ['id' => $user->id]);

        $this->assertResponseOk();
        $this->seeIsNotSoftDeletedInDatabase('users', ['id' => $user->id, 'status' => Status::ENABLED]);
    }

    public function test_admin_can_disable_user_that_is_enabled()
    {
        $user = factory(User::class)->create(['status' => Status::ENABLED]);
        $this->beLoggedInAsAdmin();

        $this->post('users/toggle', ['id' => $user->id]);

        $this->assertResponseOk();
        $this->seeIsSoftDeletedInDatabase('users', ['id' => $user->id, 'status' => Status::DISABLED]);
    }

    public function test_admin_cannot_toggle_themselves()
    {
        $user = $this->beLoggedInAsAdmin(['status' => Status::ENABLED]);

        $this->post('users/toggle', ['id' => $user->id]);

        $this->assertResponseStatus(500);
        $this->assertEquals('You cannot enable/disable yourself.', $this->response->getData(true)['message']);
    }
}
