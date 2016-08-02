<?php

namespace App\Services\Vendors\RecordedFuture;

use App\Entities\Company;
use App\Events\InstanceCreatedEvent;
use App\Services\Vendors\RecordedFuture\Api\Response;
use App\Services\Vendors\RecordedFuture\Repository as RecordedFutureRepository;
use Illuminate\Support\Facades\File;

class QueueProcessor
{
    /**
     * The Recorded Future repository for saving things from the API calls.
     *
     * @var RecordedFutureRepository
     */
    protected $recordedFutureRepo;

    public function __construct(RecordedFutureRepository $repo)
    {
        $this->recordedFutureRepo = $repo;
    }

    /**
     * Process all the instance responses for each company.
     *
     * @param bool $deleteProcessedFiles
     * @return array
     */
    public function process(bool $deleteProcessedFiles = true)
    {
        $createdInstanceIds = [];
        $filesProcessed = [];
        foreach (File::allFiles(InstanceApiResponseQueue::getFullPath()) as $queuedResponseFile) {
            // This should be the sub-directory the file is in.
            $companyRecordedFutureEntityId = $queuedResponseFile->getRelativePath();
            if ($company = Company::whereEntityId($companyRecordedFutureEntityId)->first()) {
                $response = new Response(json_decode($queuedResponseFile->getContents(), true));

                $filesProcessed[] = $queuedResponseFile->getFilename();
                foreach ($response->getInstances() as $instance) {
                    if ($instanceId = $this->recordedFutureRepo->saveInstanceForCompany($instance, $company)) {
                        $createdInstanceIds[] = $instanceId;
                    }
                }
                if ($deleteProcessedFiles) {
                    unlink($queuedResponseFile);
                }
            }
        }

        foreach ($createdInstanceIds as $instanceId) {
            event(new InstanceCreatedEvent($instanceId));
        }

        return $filesProcessed;
    }
}
