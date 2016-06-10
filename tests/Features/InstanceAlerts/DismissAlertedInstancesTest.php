<?php

namespace Tests\Features\InstanceAlerts;


use App\Entities\Instance;

class DismissAlertedInstancesTest extends \TestCase
{

    public function testDismissAlertedInstance() {
        $instance = factory(Instance::class)->create();
        $user = $this->beLoggedInAsUser();
        \DB::table('user_instance_alerts')->insert([
            'user_id' => $user->id,
            'instance_id' => $instance->id
        ]);

        $this->apiCall('GET', 'instance/alerts/dismiss/' . $instance->id);
        $this->assertResponseOk();
        $this->seeInDatabase('user_instance_alerts', [
            'user_id' => $user->id,
            'instance_id' => $instance->id,
            'dismissed' => true
        ]);
    }
}