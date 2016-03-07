<?php

namespace Tests\Features\UserCreation\Admin;

use App\Entities\Role;

class AdminCanCreateAnyTypeOfUserTest extends \TestCase
{
    public function RoleDataProvider()
    {
        return [
            Role::values()
        ];
    }

    /** @dataProvider RoleDataProvider */
    public function test_admins_can_create_all_types_of_users($role)
    {
        $this->beLoggedInAsAdmin();
        $postParams = [
            'name' => 'some name',
            'email' => 'test@test.com',
            'password' => 'somepasswordthatworks',
            'password_confirmation' => 'somepasswordthatworks',
            'role' => $role
        ];
        $this->apiCall('post', 'create-admin', $postParams);
        $responseData = json_decode($this->response->getContent(), true);

        $this->assertResponseOk();
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('created_at', $responseData);
        $this->assertArrayHasKey('updated_at', $responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertEquals($postParams['name'], $responseData['name']);
        $this->assertArrayHasKey('email', $responseData);
        $this->assertEquals($postParams['email'], $responseData['email']);
        $this->assertArrayHasKey('role', $responseData);
        $this->assertEquals($postParams['role'], $responseData['role']);

        $this->seeInDatabase('users', [
            'name' => $postParams['name'],
            'email' => $postParams['email'],
            'role' => $postParams['role']
        ]);
    }
}
