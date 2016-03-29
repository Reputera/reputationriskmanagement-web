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

        $this->call('post', 'create-user', [
            'name' => str_repeat('l', 256),
            'email' => 'test',
            'password' => str_repeat('l', 2),
            'role' => Role::USER,
            'company_id' => $company->id
        ]);

        $this->assertEquals(
            $this->getFieldLimitValidationErrors(),
            \Session::get('errors')->toArray()
        );
    }

    protected function getFieldLimitValidationErrors()
    {
        return [
            'name' => ['The name may not be greater than 255 characters.'],
            'email' => ['The email must be a valid email address.'],
            'password' => ['The password confirmation does not match.', 'The password must be at least 6 characters.'],
        ];
    }
}
