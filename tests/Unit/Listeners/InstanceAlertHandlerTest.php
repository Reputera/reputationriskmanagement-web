<?php

namespace App\Listeners\InstanceAlertHandler;



use App\Entities\Instance;
use App\Entities\User;
use App\Events\InstanceCreatedEvent;
use App\Listeners\InstanceAlertHandler;
use App\Repositories\UserRepository;

class InstanceAlertHandlerTest extends \TestCase
{

    /**
     * @var UserRepository | \Mockery\MockInterface
     */
    protected $userRepository;

    /**
     * @var InstanceAlertHandler
     */
    protected $instanceAlertHandler;

    public function setUp()
    {
        parent::setUp();

        $this->userRepository = \Mockery::mock(UserRepository::class);
        $this->instanceAlertHandler = new InstanceAlertHandler($this->userRepository);
    }

    public function testHandle()
    {
//        Users which should not be inserted to instance alerts table.
        factory(User::class)->times(2)->create();

        $instance = factory(Instance::class)->create();
        $alertedUsers = factory(User::class)->times(2)->create();

        $this->userRepository->shouldReceive(['getAlertedUserIdsForInstanceId' => $alertedUsers->lists('id')->toArray()]);
        $this->instanceAlertHandler->handle(new InstanceCreatedEvent($instance->id));
        $this->assertEquals(\DB::table('user_instance_alerts')->count(), $alertedUsers->count());
        $this->seeInDatabase('user_instance_alerts', ['user_id' => $alertedUsers[0]->id, 'instance_id' => $instance->id]);
        $this->seeInDatabase('user_instance_alerts', ['user_id' => $alertedUsers[1]->id, 'instance_id' => $instance->id]);
    }

}