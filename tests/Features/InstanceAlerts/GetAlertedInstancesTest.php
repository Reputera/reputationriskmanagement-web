<?php

namespace Tests\Features\InstanceAlerts;


use App\Entities\Instance;

class GetAlertedInstancesTest extends \TestCase
{

    public function testGetAlertedInstances() {
        factory(Instance::class)->create();
        $instance = factory(Instance::class)->create();
        $user = $this->beLoggedInAsUser();
        \DB::table('user_instance_alerts')->insert([
            'user_id' => $user->id,
            'instance_id' => $instance->id
        ]);

        $this->apiCall('GET', 'instance/alerts');
        $response = $this->getResponseData();
        $this->assertCount(1, $response);
        $this->assertEquals($instance->id, $response[0]['id']);
    }

    public function testGetAlertedInstancesReturnsDismissed() {
        $instance = factory(Instance::class)->create();
        $user = $this->beLoggedInAsUser();
        \DB::table('user_instance_alerts')->insert([
            'user_id' => $user->id,
            'instance_id' => $instance->id,
            'dismissed' => true
        ]);

        $this->apiCall('GET', 'instance/alerts', ['dismissed' => true]);
        $response = $this->getResponseData();
        $this->assertCount(1, $response);
        $this->assertEquals($instance->id, $response[0]['id']);
    }

    public function testGetAlertedInstancesDoesntReturnDismissed() {
        $dismissedInstance = factory(Instance::class)->create();
        $instance = factory(Instance::class)->create();
        $user = $this->beLoggedInAsUser();
        \DB::table('user_instance_alerts')->insert([
            'user_id' => $user->id,
            'instance_id' => $instance->id,
        ]);
        \DB::table('user_instance_alerts')->insert([
            'user_id' => $user->id,
            'instance_id' => $dismissedInstance->id,
            'dismissed' => true
        ]);
        $this->apiCall('GET', 'instance/alerts');
        $response = $this->getResponseData();
        $this->assertCount(1, $response);
        $this->assertEquals($instance->id, $response[0]['id']);
    }

}