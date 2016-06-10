<?php

namespace Tests\Unit\Entities;


use App\Entities\Instance;
use App\Entities\User;

class UserAlertTest extends \TestCase
{

    public function testGetAlertedInstances() {
        factory(Instance::class)->create();
        $instance = factory(Instance::class)->create();
        $user = factory(User::class)->create();
        \DB::table('user_instance_alerts')->insert([
            'user_id' => $user->id,
            'instance_id' => $instance->id
        ]);
        $results = $user->getAlertedInstances();
        $this->assertCount(1, $results);
        $this->assertEquals($instance->id, $results->first()->id);
    }

    public function testGetAlertedInstancesDoesntReturnDismissed() {
        $dismissedInstance = factory(Instance::class)->create();
        $instance = factory(Instance::class)->create();
        $user = factory(User::class)->create();
        \DB::table('user_instance_alerts')->insert([
            'user_id' => $user->id,
            'instance_id' => $instance->id,
        ]);
        \DB::table('user_instance_alerts')->insert([
            'user_id' => $user->id,
            'instance_id' => $dismissedInstance->id,
            'dismissed' => true
        ]);
        $results = $user->getAlertedInstances();
        $this->assertCount(1, $results);
        $this->assertEquals($instance->id, $results->first()->id);
    }

}