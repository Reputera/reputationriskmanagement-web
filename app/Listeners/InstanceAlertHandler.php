<?php

namespace App\Listeners;


use App\Events\InstanceCreatedEvent;
use App\Repositories\UserRepository;

class InstanceAlertHandler
{

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * When an instance is passed in, associate with all users who should be alerted of it through the user_instance_alerts table.
     *
     * @param InstanceCreatedEvent $instanceCreatedEvent
     */
    public function handle(InstanceCreatedEvent $instanceCreatedEvent)
    {
        $alertIds = $this->userRepository->getAlertedUserIdsForInstanceId($instanceCreatedEvent->instanceId);
        \DB::table('user_instance_alerts')->insert(
            $this->buildInsertArray($alertIds, $instanceCreatedEvent->instanceId)
        );
    }

    /**
     * @param array $ids
     * @param $instanceId
     * @return array
     */
    protected function buildInsertArray(array $ids, $instanceId) {
        $returnArray = [];
        foreach($ids as $id) {
            $returnArray[] = ['user_id' => $id, 'instance_id' => $instanceId];
        }
        return $returnArray;
    }
}