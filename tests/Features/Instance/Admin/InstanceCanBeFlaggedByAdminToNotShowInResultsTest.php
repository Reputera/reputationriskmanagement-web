<?php

namespace Tests\Features\Instance\Admin;

use App\Entities\Instance;

class InstanceCanBeFlaggedByAdminToNotShowInResultsTest extends \TestCase
{
    public function testAdminCanFlagInstance()
    {
        $instance = factory(Instance::class)->create();

        $this->beLoggedInAsAdmin();
        $this->call('POST', 'flagInstance', ['id' => $instance->id]);

        $this->assertResponseOk();
        $this->seeIsSoftDeletedInDatabase('instances', ['id' => $instance->id]);
    }

    public function testAdminCanUnFlagInstance()
    {
        $instance = factory(Instance::class)->create(['deleted_at' => date('Y-m-d H:i:s')]);

        $this->beLoggedInAsAdmin();
        $this->call('POST', 'flagInstance', ['id' => $instance->id]);

        $this->assertResponseOk();
        $this->seeIsNotSoftDeletedInDatabase('instances', ['id' => $instance->id]);
    }
}
