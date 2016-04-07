<?php

namespace Tests\Unit\Console\Commands\RecordedFuture\Instances;

use App\Services\Vendors\RecordedFuture\QueueProcessor;
use Tests\TestingCommandTrait;

class QueueProcessorTest extends \TestCase
{
    use TestingCommandTrait;
    
    /** @var QueueProcessor|\Mockery\MockInterface */
    protected $mockedQueueProcessor;

    public function setUp()
    {
        parent::setUp();
        $this->mockedQueueProcessor = \Mockery::mock(QueueProcessor::class);

        app()->instance(QueueProcessor::class, $this->mockedQueueProcessor);

        $this->createCommand();
    }

    public function testNoCompaniesCanBeFound()
    {
        $this->mockedQueueProcessor
            ->shouldReceive('process')
            ->once()
            ->withNoArgs();

        $this->command->call('recorded-future:process-instance-queue');
    }
}
