<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait TestingCommandTrait
{
    /** @var Kernel $command */
    protected $command;

    protected function createCommand()
    {
        $this->command = $this->app[Kernel::class];
    }

    protected function getCommandOutput()
    {
        return trim($this->command->output());
    }
}
