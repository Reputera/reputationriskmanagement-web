<?php

namespace Tests\Features\Company;


use App\Entities\Company;

class UpdateCompanyTest extends \TestCase
{
    public function testUpdate()
    {
        $company = factory(Company::class)->create();
        $this->beLoggedInAsAdmin();
        $this->call('POST', '/company/update/' . $company->id, [
            'max_alert_threshold' => -10,
            'min_alert_threshold' => 20
        ]);
        $this->assertResponseOk();
        $this->seeInDatabase('companies', [
            'id' => $company->id,
            'max_alert_threshold' => -10,
            'min_alert_threshold' => 20
        ]);
    }
}