<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture;

use App\Entities\Company;
use App\Services\Vendors\RecordedFuture\Api\Instance;
use App\Services\Vendors\RecordedFuture\QueueProcessor;
use App\Services\Vendors\RecordedFuture\InstanceApiResponseQueue;
use App\Services\Vendors\RecordedFuture\Repository;
use Symfony\Component\Finder\SplFileInfo;
use Tests\StubData\RecordedFuture\SingleInstance;

class QueueProcessorTest extends \TestCase
{
    /** @var SplFileInfo|\Mockery\MockInterface */
    protected $mockedFile;

    /** @var Repository|\Mockery\MockInterface */
    protected $mockedInstanceRepo;

    /** @var QueueProcessor */
    protected $queueProcessor;

    /** @var array */
    protected $companyCounterArray = [];

    public function setUp()
    {
        parent::setUp();
        $this->mockedFile = \Mockery::mock(SplFileInfo::class);
        $this->mockedInstanceRepo  = \Mockery::mock(Repository::class);
        $this->queueProcessor =  new QueueProcessor($this->mockedInstanceRepo);
    }

    public function testProcessingWhenFilesExist()
    {
        $company = factory(Company::class)->create();

        $returnArrayOfFiles = [
            $this->setupFileMockForCompany($company),
            $this->setupFileMockForCompany($company)
        ];

        $this->mockedInstanceRepo
            ->shouldReceive('saveInstanceForCompany')
            ->times(count($returnArrayOfFiles))
            ->with(\Mockery::type(Instance::class), \Mockery::type(Company::class));

        \File::shouldReceive('allFiles')
            ->once()
            ->with(InstanceApiResponseQueue::getFullPath())
            ->andReturn($returnArrayOfFiles);

        $this->assertEquals([$company->name.'file1', $company->name.'file2'], $this->queueProcessor->process());
    }

    public function testProcessingWhenNoFilesExist()
    {
        $this->mockedInstanceRepo
            ->shouldReceive('saveInstanceForCompany')
            ->never();

        \File::shouldReceive(['getContents'])
            ->never();

        \File::shouldReceive(['getFilename'])
            ->never();

        \File::shouldReceive('allFiles')
            ->once()
            ->with(InstanceApiResponseQueue::getFullPath())
            ->andReturn([]);

        $this->assertEquals([], $this->queueProcessor->process());
    }

    public function testProcessingFilesForDifferentCompanies()
    {
        $company1 = factory(Company::class)->create();
        $company2 = factory(Company::class)->create();
        $company3 = factory(Company::class)->create();
        $returnArrayOfFiles = [
            $this->setupFileMockForCompany($company1),
            $this->setupFileMockForCompany($company2),
            $this->setupFileMockForCompany($company3),
        ];

        $this->mockedInstanceRepo
            ->shouldReceive('saveInstanceForCompany')
            ->times(count($returnArrayOfFiles))
            ->with(\Mockery::type(Instance::class), \Mockery::type(Company::class));

        \File::shouldReceive('allFiles')
            ->once()
            ->with(InstanceApiResponseQueue::getFullPath())
            ->andReturn($returnArrayOfFiles);

        $this->assertEquals(
            [$company1->name.'file1', $company2->name.'file1', $company3->name.'file1'],
            $this->queueProcessor->process()
        );
    }

    protected function setupFileMockForCompany(Company $company)
    {
        $this->companyCounterArray[$company->name][] = $company->name;

        $file = $this->mockedFile;
        $file->shouldReceive('getRelativePath')
            ->once()
            ->withNoArgs()
            ->andReturn($company->entity_id);

        $file->shouldReceive('getContents')
            ->once()
            ->withNoArgs()
            ->andReturn(SingleInstance::get([], true));

        $file->shouldReceive('getFilename')
            ->once()
            ->withNoArgs()
            ->andReturn($company->name.'file'.count($this->companyCounterArray[$company->name]));

        return $file;
    }
}
