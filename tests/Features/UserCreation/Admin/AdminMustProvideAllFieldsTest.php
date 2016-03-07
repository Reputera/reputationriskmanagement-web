<?php

namespace Tests\Features\UserCreation\Admin;

class AdminMustProvideAllFieldsTest extends \TestCase
{
    public function test_all_required_fields()
    {
        $this->beLoggedInAsAdmin();
        $this->apiCall('post', 'create-admin');

        $this->assertJsonUnprocessableEntity();
        $this->assertEquals(
            $this->getRequiredFieldValidationErrors(),
            json_decode($this->response->getContent(), true)['errors']
        );
    }

    protected function getRequiredFieldValidationErrors()
    {
        return [
            'name' => ['The name field is required.'],
            'email' => ['The email field is required.'],
            'password' => ['The password field is required.'],
            'role' => ['The role field is required.'],
        ];
    }
}
