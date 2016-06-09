<?php

namespace Tests\Unit\Services\Vendors\RecordedFuture;

use App\Entities\Company;
use App\Events\InstanceCreatedEvent;
use App\Services\Vendors\RecordedFuture\Api\Instance;
use App\Services\Vendors\RecordedFuture\QueueProcessor;
use App\Services\Vendors\RecordedFuture\InstanceApiResponseQueue;
use App\Services\Vendors\RecordedFuture\Repository;
use org\bovigo\vfs\vfsStream;
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
        $this->storageFolder = vfsStream::setup(InstanceApiResponseQueue::getFullPath());
    }

    public function testProcessingWhenFilesExist()
    {
        $this->expectsEvents(InstanceCreatedEvent::class);
        $company = factory(Company::class)->create();

        $companyDir = vfsStream::newDirectory($company->entity_id)->at($this->storageFolder);

        vfsStream::newFile('logfile1.log')->at($companyDir)
            ->setContent(SingleInstance::get([], true));
        vfsStream::newFile('logfile2.log')->at($companyDir)
            ->setContent(SingleInstance::get([], true));

        $allFiles = \File::allFiles(vfsStream::url($this->storageFolder->path()));

        $this->mockedInstanceRepo
            ->shouldReceive('saveInstanceForCompany')
            ->times(2)
            ->with(\Mockery::type(Instance::class), \Mockery::type(Company::class))
            ->andReturn(2);

        \File::shouldReceive('allFiles')
            ->once()
            ->with(InstanceApiResponseQueue::getFullPath())
            ->andReturn($allFiles);

        $this->assertTrue($companyDir->hasChild('logfile1.log'));
        $this->assertTrue($companyDir->hasChild('logfile2.log'));

        $this->assertEquals(['logfile1.log', 'logfile2.log'], $this->queueProcessor->process());

        $this->assertFalse($companyDir->hasChild('logfile1.log'));
        $this->assertFalse($companyDir->hasChild('logfile2.log'));
    }

    public function testProcessingWhenNoFilesExist()
    {
        $this->doesntExpectEvents(InstanceCreatedEvent::class);
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
        $this->expectsEvents(InstanceCreatedEvent::class);

        $company1 = factory(Company::class)->create();
        $company1Dir = vfsStream::newDirectory($company1->entity_id)->at($this->storageFolder);
        vfsStream::newFile('Company1logfile.log')->at($company1Dir)
            ->setContent(SingleInstance::get([], true));

        $company2 = factory(Company::class)->create();
        $company2Dir = vfsStream::newDirectory($company2->entity_id)->at($this->storageFolder);
        vfsStream::newFile('Company2logfile.log')->at($company2Dir)
            ->setContent(SingleInstance::get([], true));

        $company3 = factory(Company::class)->create();
        $company3Dir = vfsStream::newDirectory($company3->entity_id)->at($this->storageFolder);
        vfsStream::newFile('Company3logfile.log')->at($company3Dir)
            ->setContent(SingleInstance::get([], true));

        $allFiles = \File::allFiles(vfsStream::url($this->storageFolder->path()));
        $this->mockedInstanceRepo
            ->shouldReceive('saveInstanceForCompany')
            ->times(count($allFiles))
            ->with(\Mockery::type(Instance::class), \Mockery::type(Company::class))
            ->andReturn(2);

        \File::shouldReceive('allFiles')
            ->once()
            ->with(InstanceApiResponseQueue::getFullPath())
            ->andReturn($allFiles);

        $this->assertTrue($company1Dir->hasChild('Company1logfile.log'));
        $this->assertTrue($company2Dir->hasChild('Company2logfile.log'));
        $this->assertTrue($company3Dir->hasChild('Company3logfile.log'));

        $this->assertEquals(
            ['Company1logfile.log', 'Company2logfile.log', 'Company3logfile.log'],
            $this->queueProcessor->process()
        );

        $this->assertFalse($company1Dir->hasChild('Company1logfile.log'));
        $this->assertFalse($company2Dir->hasChild('Company2logfile.log'));
        $this->assertFalse($company3Dir->hasChild('Company3logfile.log'));
    }
}
