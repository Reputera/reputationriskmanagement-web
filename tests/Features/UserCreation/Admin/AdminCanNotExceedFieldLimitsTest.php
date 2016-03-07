<?php

namespace Tests\Features\UserCreation\Admin;

class AdminCanNotExceedFieldLimitsTest extends \TestCase
{
    public function test_fields_limits()
    {
        $this->beLoggedInAsAdmin();
        $this->apiCall('post', 'create-admin', [
            'name' => str_repeat('l', 256),
            'email' => 'test',
            'password' => str_repeat('l', 2),
            'role' => 'aRoleNotInTheListedRoles'
        ]);
        $this->assertJsonUnprocessableEntity();
        $this->assertEquals(
            $this->getFieldLimitValidationErrors(),
            json_decode($this->response->getContent(), true)['errors']
        );
    }

    protected function getFieldLimitValidationErrors()
    {
        return [
            'name' => ['The name may not be greater than 255 characters.'],
            'email' => ['The email must be a valid email address.'],
            'password' => ['The password confirmation does not match.', 'The password must be at least 6 characters.'],
            'role' => ['The selected role is invalid.'],
        ];
    }
}
