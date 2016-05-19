<?php

namespace Tests\Features\UserCreation\Admin;

use App\Entities\Company;
use App\Entities\Role;
use App\Entities\User;

class AdminCanCreateAnyTypeOfUserTest extends \TestCase
{
    protected $postParams = [];

    public function roleDataProvider()
    {
        return [
            Role::values()
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->postParams = [
            'name' => 'some name',
            'email' => 'test@test.com',
            'phone_number' => 8888888888,
            'phone_number_extension' => 7777,
            'password' => 'somepasswordthatworks',
        ];
    }

    /**
     * @param $role The role to attach to the user.
     * @dataProvider roleDataProvider
     */
    public function test_admins_can_create_all_users_of_all_roles($role)
    {
        $this->beLoggedInAsAdmin();
        $company = factory(Company::class)->create();
        $postParams = $this->postParams;
        $postParams['role'] = $role;
        $postParams['company_id'] = $company->id;

        $this->apiCall('post', 'create-user', $postParams);

        $this->assertJsonResponseOkAndFormattedProperly();

        $this->seeInDatabase('users', [
            'name' => $postParams['name'],
            'email' => $postParams['email'],
            'role' => $postParams['role'],
            'phone_number' => $postParams['phone_number'],
            'phone_number_extension' => $postParams['phone_number_extension'],
            'company_id' => ($role != 'Admin') ? $company->id : null
        ]);
    }

    public function test_phone_number_and_company_not_required_for_an_admin_user()
    {
        $this->beLoggedInAsAdmin();

        $postParams = $this->postParams;
        unset($postParams['phone_number'], $postParams['phone_number_extension']);
        $postParams['role'] = Role::ADMIN;

        $this->apiCall('post', 'create-user', $postParams);

        $this->assertJsonResponseOkAndFormattedProperly();

        $this->seeInDatabase('users', [
            'name' => $postParams['name'],
            'email' => $postParams['email'],
            'role' => $postParams['role'],
            'phone_number' => null,
            'phone_number_extension' => null,
            'company_id' => null
        ]);
    }
}
