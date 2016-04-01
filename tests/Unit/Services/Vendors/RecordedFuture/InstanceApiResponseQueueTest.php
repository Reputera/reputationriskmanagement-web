<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture;

use App\Entities\Company;
use App\Services\Vendors\RecordedFuture\Api\RecordedFutureApi;
use App\Services\Vendors\RecordedFuture\Api\Response;
use App\Services\Vendors\RecordedFuture\InstanceApiResponseQueue;
use Illuminate\Support\Facades\Storage;

class InstanceApiResponseQueueTest extends \TestCase
{
    /** @var RecordedFutureApi|\Mockery\MockInterface */
    protected $mockedApi;

    /** @var ResponseResponse|\Mockery\MockInterface */
    protected $mockedApiResponse;

    /** @var InstanceApiResponseQueue */
    protected $responseQueue;

    /** @var string */
    protected $mockedNextStartPageString = '7q8w9e5r2d4s5';

    public function setUp()
    {
        parent::setUp();
        $this->mockedApi = \Mockery::mock(RecordedFutureApi::class);
        $this->mockedApiResponse = \Mockery::mock(Response::class);
        $this->responseQueue = new InstanceApiResponseQueue($this->mockedApi);
    }

    public function testPathIsLocalStoragePath()
    {
        $this->assertEquals('RecordedFuture-responses', $this->responseQueue->getPath());
    }

    public function testProcessHourly()
    {
        $company = factory(Company::class)->create();
        $hoursToProcess = 12;

        $this->setupMocks('queryInstancesForEntityHourly', $company, $hoursToProcess);

        $this->responseQueue->processHourly($company, $hoursToProcess);
    }

    public function testProcessDaily()
    {
        $company = factory(Company::class)->create();
        $daysToProcess = 365;

        $this->setupMocks('queryInstancesForEntityDaily', $company, $daysToProcess);

        $this->responseQueue->processDaily($company, $daysToProcess);
    }

    protected function setupMocks(string $functionName, Company $company, int $measurement)
    {
        $firstResponse = clone $this->mockedApiResponse;
        $secondResponse = clone $this->mockedApiResponse;

        $firstResponse->shouldReceive([
                'countOfReferences' => 10,
                'getNextPageStart' => $this->mockedNextStartPageString,
                'recordAsJson' => 123
            ])
            ->withNoArgs()
            ->once();

        $secondResponse->shouldReceive('countOfReferences')
            ->withNoArgs()
            ->once()
            ->andReturn(0);

        $this->mockedApi->shouldReceive($functionName)
            ->with($company->entity_id, $measurement)
            ->once()
            ->andReturn($firstResponse);

        $this->mockedApi->shouldReceive($functionName)
            ->with($company->entity_id, $measurement, ['page_start' => $this->mockedNextStartPageString])
            ->once()
            ->andReturn($secondResponse);

        Storage::shouldReceive('disc')
            ->with('local')
            ->once()
            ->andReturnSelf();

        Storage::shouldReceive('put')
            ->with('/'.$this->responseQueue->getPath().'\/[0-9]{4}-[0-9]{2}-[0-9]{2}_[0-9]{2}-[0-9]{2}-[0-9]{2}_'.
                $this->mockedNextStartPageString.'-'.$company->entity_id.'.log/', '123')
            ->once()
            ->andReturnSelf();
    }
}
