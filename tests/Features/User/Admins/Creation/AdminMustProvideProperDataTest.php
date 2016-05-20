<?php

namespace Tests\Features\User\Admins\Creation;

use App\Entities\Company;
use App\Entities\Role;

class AdminMustProvideProperDataTest extends \TestCase
{
    public function test_all_required_fields()
    {
        $this->beLoggedInAsAdmin();
        $this->ajaxCall('post', 'create-user');

        $this->assertJsonUnprocessableEntity();

        $this->assertEquals(
            $this->getRequiredFieldValidationErrors(),
            $this->response->getData(true)['errors']
        );
    }

    public function test_phone_number_is_required_for_non_admin_users()
    {
        $this->beLoggedInAsAdmin();

        $allRoles = Role::all();
        unset($allRoles['ADMIN']);
        $company = factory(Company::class)->create();

        foreach ($allRoles as $role) {
            $postData = [
                'name' => 'some name',
                'email' => 'test@test.com',
                'password' => 'somepasswordthatworks',
                'role' => $role,
                'company_id' => $company->id
            ];

            $this->ajaxCall('post', 'create-user', $postData);

            $this->assertJsonUnprocessableEntity();

            $errors = $this->response->getData(true)['errors'];

            $this->assertInternalType('array', $errors);
            $this->assertCount(1, $errors);
            $this->assertArrayHasKey('phone_number', $errors);
            $this->assertEquals($this->getRequiredFieldValidationErrors()['phone_number'], $errors['phone_number']);
        }
    }

    protected function getRequiredFieldValidationErrors()
    {
        return [
            'name' => ['The name field is required.'],
            'email' => ['The email field is required.'],
            'password' => ['The password field is required.'],
            'role' => ['The role field is required.'],
            'company_id' => ['The company field is required.'],
            'phone_number' => ['The phone number field is required.']
        ];
    }
}
