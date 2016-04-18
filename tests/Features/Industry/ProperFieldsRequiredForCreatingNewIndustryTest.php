<?php

namespace Tests\Features\Industry;

class ProperFieldsRequiredForCreatingNewIndustryTest extends \TestCase
{
    public function testRequiredFields()
    {
        $this->beLoggedInAsAdmin();

        $this->apiCall('POST', 'industry');

        $this->assertJsonUnprocessableEntity();
        $this->assertContains(
            '{"industry_name":["The industry name field is required."]}',
            $this->response->getContent()
        );
    }
}
