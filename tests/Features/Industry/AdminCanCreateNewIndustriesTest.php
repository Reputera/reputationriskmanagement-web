<?php

namespace Tests\Features\Industry;

class AdminCanCreateNewIndustriesTest extends \TestCase
{
    public function testAdminCanCreateNewIndustry()
    {
        $this->beLoggedInAsAdmin();

        $newIndustryName = 'Some New Industry';

        $this->dontSeeInDatabase('industries', ['name' => $newIndustryName]);
        $this->apiCall('POST', 'industry', ['industry_name' => $newIndustryName]);
        $this->seeInDatabase('industries', ['name' => $newIndustryName]);
    }
}
