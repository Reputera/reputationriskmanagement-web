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

    public function testGetAlertedInstancesWithinDateRange() {
        $notReturnedInstance = factory(Instance::class)->create(['start' => '2016-07-10 17:48:47']);
        $returnedInstance = factory(Instance::class)->create(['start' => '2016-07-14 00:10:00']);
        $user = $this->beLoggedInAsUser();
        \DB::table('user_instance_alerts')->insert([
            'user_id' => $user->id,
            'instance_id' => $notReturnedInstance->id,
        ]);
        \DB::table('user_instance_alerts')->insert([
            'user_id' => $user->id,
            'instance_id' => $returnedInstance->id,
        ]);
        $this->apiCall('GET', 'instance/alerts', [
            'start_datetime' => '2016-07-13 17:48:47',
            'end_datetime' => '2016-07-14 17:48:47'
        ]);
        $response = $this->getResponseData();
        $this->assertCount(1, $response);
        $this->assertEquals($returnedInstance->id, $response[0]['id']);
    }

}