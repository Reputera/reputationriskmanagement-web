<?php

namespace Tests\Features\UserCreation\Admin;

class AdminMustProvideAllFieldsTest extends \TestCase
{
    public function test_all_required_fields()
    {
        $this->beLoggedInAsAdmin();
        $this->call('post', 'create-user');

        $this->assertEquals(
            $this->getRequiredFieldValidationErrors(),
            \Session::get('errors')->toArray()
        );
    }

    protected function getRequiredFieldValidationErrors()
    {
        return [
            'name' => ['The name field is required.'],
            'email' => ['The email field is required.'],
            'password' => ['The password field is required.'],
            'role' => ['The role field is required.'],
            'company_id' => ['The company field is required.']
        ];
    }
}
