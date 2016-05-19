<?php

namespace Tests\Features\UserCreation\Admin;

use App\Entities\Company;
use App\Entities\Role;

class AdminCanNotExceedFieldLimitsTest extends \TestCase
{
    public function test_fields_limits()
    {
        $company = factory(Company::class)->create();
        $this->beLoggedInAsAdmin();

        $this->ajaxCall('post', 'create-user', [
            'name' => str_repeat('l', 256),
            'email' => 'test',
            'password' => str_repeat('l', 2),
            'role' => Role::USER,
            'company_id' => $company->id,
            'phone_number' => str_repeat(1, 11),
            'phone_number_extension' => str_repeat(1, 51)
        ]);

        $this->assertJsonUnprocessableEntity();

        $this->assertEquals(
            $this->getFieldLimitValidationErrors(),
            $this->response->getData(true)['errors']
        );
    }

    protected function getFieldLimitValidationErrors()
    {
        return [
            'name' => ['The name may not be greater than 255 characters.'],
            'email' => ['The email must be a valid email address.'],
            'password' => ['The password must be at least 6 characters.'],
            'phone_number' => ['The phone number must be 10 numbers only.'],
            'phone_number_extension' => ['The phone number extension must be a maximum of 10 numbers only.'],
        ];
    }
}
