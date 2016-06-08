<?php

namespace Tests\Features\Validation;


use App\Entities\Role;

class RequiredForRoleValidationTest extends \TestCase
{

    public function testValidationFailsIfAdminAndMessageIsGiven()
    {
        $this->beLoggedInAsAdmin();
        $validator = \Validator::make(
            [],
            ['input' => 'required_for_role:' . Role::ADMIN]
        );
        $this->assertFalse($validator->passes());
        $this->assertEquals('The input field is required for the current user\'s role', $validator->messages()->first());
    }

    public function testValidationSucceedsIfAdmin()
    {
        $this->beLoggedInAsAdmin();
        $validator = \Validator::make(
            ['input' => 'test'],
            ['input' => 'required_for_role:' . Role::ADMIN]
        );
        $this->assertTrue($validator->passes());
    }

    public function testValidationSucceedsIfNotAdmin()
    {
        $this->beLoggedInAsUser(['role' => Role::USER]);
        $validator = \Validator::make(
            [],
            ['input' => 'required_for_role:' . Role::ADMIN]
        );
        $this->assertTrue($validator->passes());
    }

}