<?php

namespace Tests\Features\Company;

use App\Entities\Industry;
use App\Jobs\QueueYearlyRecordedFutureInstances;
use Illuminate\Contracts\Bus\Dispatcher;

class AdminCanCreateMultipleNewCompaniesTest extends \TestCase
{
    public function testAnAdminCanCreateMultipleCompanies()
    {
        $mockedDispatcher = \Mockery::mock(Dispatcher::class);
        $this->beLoggedInAsAdmin();
        $industry = factory(Industry::class)->create();
        $mockedDispatcher->shouldReceive('dispatch')
            ->twice()
            ->with(\Mockery::type(QueueYearlyRecordedFutureInstances::class));

        $postData = ['companies' => [
            ['name' => 'Company 1', 'entity_id' => '1', 'industry_id' => $industry->id],
            ['name' => 'Company 2', 'entity_id' => '2', 'industry_id' => $industry->id]
        ]];

        app()->instance(Dispatcher::class, $mockedDispatcher);
        $this->call('POST', 'create-company', $postData);

        $this->assertJsonResponseOkAndFormattedProperly();
        foreach ($this->response->getData(true)['data'] as $key => $company) {
            $this->assertArrayHasKeys(['id', 'name', 'entity_id'], $company);
            $this->assertNotEmpty($company['id']);
            $this->assertEquals($company['name'], $postData['companies'][$key]['name']);
            $this->assertEquals($company['entity_id'], $postData['companies'][$key]['entity_id']);
        }

        $this->seeInDatabase('companies', [
            'name' => $postData['companies'][0]['name'],
            'entity_id' => $postData['companies'][0]['entity_id'],
        ]);
        $this->seeInDatabase('companies', [
            'name' => $postData['companies'][1]['name'],
            'entity_id' => $postData['companies'][1]['entity_id'],
        ]);

        $this->seeInDatabase('company_alert_parameters', [
            'company_id' => $company['id'],
            'min_threshold' => config('rrm.default_alerts.0.min_threshold'),
            'max_threshold' => config('rrm.default_alerts.0.max_threshold')
        ]);

    }
}
