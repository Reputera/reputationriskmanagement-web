<?php

namespace Tests\Features\Company;

use App\Entities\Industry;

class AdminCanCreateMultipleNewCompaniesTest extends \TestCase
{
    public function testAnAdminCanCreateMultipleCompanies()
    {
        $this->beLoggedInAsAdmin();
        $industry = factory(Industry::class)->create();

        $postData = ['companies' => [
            ['name' => 'Company 1', 'entity_id' => '1', 'industry_id' => $industry->id],
            ['name' => 'Company 2', 'entity_id' => '2', 'industry_id' => $industry->id]
        ]];
        $this->apiCall('POST', 'create-company', $postData);

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
    }
}
