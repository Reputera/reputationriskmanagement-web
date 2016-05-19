<?php

namespace Tests\Features\Industry;

class AdminCanCreateNewIndustriesTest extends \TestCase
{
    public function testAdminCanCreateNewIndustry()
    {
        $this->beLoggedInAsAdmin();

        $newIndustryName = 'Some New Industry';

        $this->dontSeeInDatabase('industries', ['name' => $newIndustryName]);
        $this->ajaxCall('POST', 'industry', ['industry_name' => $newIndustryName]);
        $this->seeInDatabase('industries', ['name' => $newIndustryName]);
        
        $responseJsonString = $this->response->getContent();
        $this->assertJsonResponseOkAndFormattedProperly();
        $this->assertContains('"id"', $responseJsonString);
        $this->assertContains('"name":"'.$newIndustryName.'"', $responseJsonString);
    }
}
