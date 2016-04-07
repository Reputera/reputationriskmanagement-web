<?php

namespace Tests\Unit\Console\Commands\SystemCleanup;

use Carbon\Carbon;
use Tests\TestingCommandTrait;

class InstanceTest extends \TestCase
{
    use TestingCommandTrait;

    public function setUp()
    {
        parent::setUp();
        $this->createCommand();
    }

    public function testItWorks()
    {
        $this->markTestSkipped('Not ready...');

        $dateToday = Carbon::now();
        $dateLastYear = clone $dateToday;
        $dateLastYear->subYear(1);

        $this->assertRemotePathExists($dateToday);
        $this->assertLocalPathExists();

        $this->command->call('system-cleanup:instances');
    }

    protected function assertRemotePathExists(Carbon $dateToday)
    {
        \Storage::shouldReceive('disk')
            ->with('s3')
            ->andReturnSelf();

        \Storage::shouldReceive('exists')
            ->with($dateToday->year.'/'.$dateToday->format('m (F)').'/'.$dateToday->format('d (D)'))
            ->andReturn(true);
    }

    protected function assertLocalPathExists()
    {
        \Storage::shouldReceive('disk')
            ->with('local')
            ->andReturnSelf();

        \Storage::shouldReceive('isDirectory')
            ->with(storage_path('temp_instance_backups'))
            ->andReturnSelf();
    }
}
