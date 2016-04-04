<?php

namespace Tests\Unit\Console\Commands\RecordedFuture\Instances;

use App\Entities\Company;
use Tests\TestingCommandTrait;

class RetrieveInstanceResponsesHourlyTest extends \TestCase
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
        $this->command->call('recorded-future:queue-instances-hourly');
        $this->assertEquals('No company/companies found.', $this->getCommandOutput());

        // Test with no companies but one is specified.
        $this->command->call('recorded-future:queue-instances-hourly', ['--company' => 'test']);
        $this->assertEquals('No company/companies found.', $this->getCommandOutput());

        // Test with companies present, but the specified on doesn't exist.
        $company = factory(Company::class)->create();
        $this->command->call('recorded-future:queue-instances-hourly', ['--company' => $company->name.'oops']);
        $this->assertEquals('No company/companies found.', $this->getCommandOutput());
    }

    public function testAllCompaniesFoundAndQueuedWithDefaultHours()
    {
        $this->setupMockedAllCompaniesForCadence('queueInstancesHourly', 1);

        $this->command->call('recorded-future:queue-instances-hourly');
        $this->assertEmpty($this->getCommandOutput());
    }

    public function testAllCompaniesFoundAndQueuedWithGivenDays()
    {
        $hoursToProcess = 2;
        $this->setupMockedAllCompaniesForCadence('queueInstancesHourly', $hoursToProcess);

        $this->command->call('recorded-future:queue-instances-hourly', ['--hours' => $hoursToProcess]);
        $this->assertEmpty($this->getCommandOutput());
    }

    public function testSingleCompanyFoundAndQueuedWithDefaultDays()
    {
        $companyName = 'Testing';
        $this->setupMockedCompanyForCadence('queueInstancesHourly', 1, $companyName);

        $this->command->call('recorded-future:queue-instances-hourly', ['--company' => $companyName]);
        $this->assertEmpty($this->getCommandOutput());
    }

    public function testSingleCompanyFoundAndQueuedWithGivenDays()
    {
        $companyName = 'Testing';
        $givenDays = 12;
        $this->setupMockedCompanyForCadence('queueInstancesHourly', $givenDays, $companyName);

        $this->command->call('recorded-future:queue-instances-hourly', [
            '--company' => $companyName,
            '--hours' => $givenDays
        ]);
        $this->assertEmpty($this->getCommandOutput());
    }
}
