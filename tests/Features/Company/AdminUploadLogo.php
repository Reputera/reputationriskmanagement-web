<?php

namespace Tests\Features\Company;


use App\Entities\Company;

class AdminUploadLogo extends \TestCase
{

    public function testUpload() {
        $company = factory(Company::class)->create();
        $this->beLoggedInAsAdmin();
        $this->apiCall('POST', 'edit-company-logo', [
            'company_id' => $company->id,
            'logoImage' => base64_encode('test')
        ]);
        dd($this->response);
    }

}