<?php

namespace Tests\Unit\Console\Commands\RecordedFuture\Instances;

use App\Entities\Company;
use Tests\TestingCommandTrait;

class RetrieveInstanceResponsesDailyTest extends \TestCase
{
    use TestingCommandTrait, MockedCompaniesCadence;

    public function setUp()
    {
        parent::setUp();
        $this->createCommand();
    }

    public function testNoCompaniesCanBeFound()
    {
        // Test with no companies at all.
        $this->command->call('recorded-future:queue-instances-daily');
        $this->assertEquals('No company/companies found.', $this->getCommandOutput());

        // Test with no companies but one is specified.
        $this->command->call('recorded-future:queue-instances-daily', ['--company' => 'test']);
        $this->assertEquals('No company/companies found.', $this->getCommandOutput());

        // Test with companies present, but the specified on doesn't exist.
        $company = factory(Company::class)->create();
        $this->command->call('recorded-future:queue-instances-daily', ['--company' => $company->name.'oops']);
        $this->assertEquals('No company/companies found.', $this->getCommandOutput());
    }

    public function testAllCompaniesFoundAndQueuedWithDefaultDays()
    {
        $this->setupMockedAllCompaniesForCadence('queueInstancesDaily', 1);

        $this->command->call('recorded-future:queue-instances-daily');
        $this->assertEmpty($this->getCommandOutput());
    }

    public function testAllCompaniesFoundAndQueuedWithGivenDays()
    {
        $daysToProcess = 5;
        $this->setupMockedAllCompaniesForCadence('queueInstancesDaily', $daysToProcess);

        $this->command->call('recorded-future:queue-instances-daily', ['--days' => $daysToProcess]);
        $this->assertEmpty($this->getCommandOutput());
    }

    public function testSingleCompanyFoundAndQueuedWithDefaultDays()
    {
        $companyName = 'Testing';
        $this->setupMockedCompanyForCadence('queueInstancesDaily', 1, $companyName);

        $this->command->call('recorded-future:queue-instances-daily', ['--company' => $companyName]);
        $this->assertEmpty($this->getCommandOutput());
    }

    public function testSingleCompanyFoundAndQueuedWithGivenDays()
    {
        $companyName = 'Testing';
        $givenDays = 5;
        $this->setupMockedCompanyForCadence('queueInstancesDaily', $givenDays, $companyName);

        $this->command->call('recorded-future:queue-instances-daily', [
            '--company' => $companyName,
            '--days' => $givenDays
        ]);
        $this->assertEmpty($this->getCommandOutput());
    }
}
