<?php

namespace App\Events;


class InstanceCreatedEvent extends Event
{

    /**
     * ID of created instance.
     *
     * @var int $instanceIds
     */
    public $instanceId;

    public function __construct(int $instanceId)
    {
        $this->instanceId = $instanceId;
    }

}