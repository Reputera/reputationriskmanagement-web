<?php

namespace Tests\Features\UserCreation\Admin;

use App\Entities\Company;
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
        $company = factory(Company::class)->create();
        $postParams = [
            'name' => 'some name',
            'email' => 'test@test.com',
            'password' => 'somepasswordthatworks',
            'password_confirmation' => 'somepasswordthatworks',
            'role' => $role,
            'company_id' => $company->id,
        ];

        $this->call('post', 'create-user', $postParams);

        $this->assertRedirectedToRoute('adminUser.get');

        $this->seeInDatabase('users', [
            'name' => $postParams['name'],
            'email' => $postParams['email'],
            'role' => $postParams['role'],
            'company_id' => ($role != 'Admin') ? $company->id : null
        ]);
    }
}
