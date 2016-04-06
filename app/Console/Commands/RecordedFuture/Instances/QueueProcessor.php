<?php

namespace App\Console\Commands\RecordedFuture\Instances;

use App\Services\Vendors\RecordedFuture\QueueProcessor as RFQueueProcessor;
use Illuminate\Console\Command;

class QueueProcessor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recorded-future:process-instance-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all instance in the queue.';

    /**
     * The Recorded Future Queue Processor.
     *
     * @var RFQueueProcessor
     */
    protected $queueProcessor;

    public function __construct(RFQueueProcessor $processor)
    {
        parent::__construct();
        $this->queueProcessor = $processor;
    }

    public function handle()
    {
        $this->queueProcessor->process();
    }
}
