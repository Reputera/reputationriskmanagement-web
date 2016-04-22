<?php

namespace Tests\Unit\Companies;

use App\Entities\Company;
use App\Http\Requests\Company\NewCompanyRequest;

class AddingCompaniesValidationTest extends \TestCase
{
    public function testCompaniesAreRequired()
    {
        $this->beLoggedInAsAdmin();

        $this->apiCall('POST', 'create-company', []);

        $this->assertJsonUnprocessableEntity();
        $this->assertContains('["Please enter all the company(s) data."]', $this->response->getContent());

        $this->apiCall('POST', 'create-company', ['companies' => []]);

        $this->assertJsonUnprocessableEntity();
        $this->assertContains('["Please enter all the company(s) data."]', $this->response->getContent());
    }

    public function testMaximumNumberOfCompaniesThatCanBeCreated()
    {
        $this->beLoggedInAsAdmin();

        $data = ['companies' => []];
        for ($x = 1; $x <= (NewCompanyRequest::MAX_COMPANIES_TO_CREATE + 1); $x++) {
            $data['companies'][] = [];
        }

        $this->apiCall('POST', 'create-company', $data);

        $this->assertJsonUnprocessableEntity();
        $this->assertContains(
            '["Between '.NewCompanyRequest::MIN_COMPANIES_TO_CREATE.' and '.NewCompanyRequest::MAX_COMPANIES_TO_CREATE.' companies can be created at once."]',
            $this->response->getContent()
        );
    }

    public function testCompanyNamesMustBeUniqueAndNotInTheDatabase()
    {
        $this->beLoggedInAsAdmin();

        $data = ['companies' => [
            ['name' => 'Name 1'],
            ['name' => 'Name 1'],
        ]];

        $this->apiCall('POST', 'create-company', $data);

        $this->assertJsonUnprocessableEntity();
        $this->assertContains(
            '["The company is already in the list to be created."]',
            $this->response->getContent()
        );

        factory(Company::class)->create(['name' => 'Name 1']);
        $data = ['companies' => [
            ['name' => 'Name 1']
        ]];

        $this->apiCall('POST', 'create-company', $data);

        $this->assertJsonUnprocessableEntity();
        $this->assertContains(
            '["The company name already exists in the system."]',
            $this->response->getContent()
        );
    }

    public function testCompanyEntityIdMustBeUniqueAndNotInTheDatabase()
    {
        $this->beLoggedInAsAdmin();

        $data = ['companies' => [
            ['entity_id' => 'Name 1'],
            ['entity_id' => 'Name 1'],
        ]];

        $this->apiCall('POST', 'create-company', $data);

        $this->assertJsonUnprocessableEntity();
        $this->assertContains(
            '["A company with this Recorded Future entity ID is already in the list to be created."]',
            $this->response->getContent()
        );

        factory(Company::class)->create(['entity_id' => 'Name 1']);
        $data = ['companies' => [
            ['entity_id' => 'Name 1']
        ]];

        $this->apiCall('POST', 'create-company', $data);

        $this->assertJsonUnprocessableEntity();
        $this->assertContains(
            '["The Recorded Future entity ID is already in the system."]',
            $this->response->getContent()
        );
    }

    public function testIndustryIdMustBeInTheSystem()
    {
        $this->beLoggedInAsAdmin();

        factory(Company::class)->create(['entity_id' => 'Name 1']);
        $data = ['companies' => [
            ['entity_id' => 'Name 1', 'industry_id' => 99]
        ]];

        $this->apiCall('POST', 'create-company', $data);

        $this->assertJsonUnprocessableEntity();
        $this->assertContains(
            '["The industry must exist in the system."]',
            $this->response->getContent()
        );
    }

    public function testAllRequiredFields()
    {
        $this->beLoggedInAsAdmin();
        $data = ['companies' => [
            []
        ]];

        $this->apiCall('POST', 'create-company', $data);

        $this->assertJsonUnprocessableEntity();
        $responseContent = $this->response->getContent();
        $this->assertContains(
            '"The company name is required."',
            $responseContent
        );
        $this->assertContains(
            '"A Recorded Future entity ID is required."',
            $responseContent
        );
        $this->assertContains(
            '"An industry for a company is required."',
            $responseContent
        );
    }
}
