<?php

namespace Tests\Features\Validation;


use App\Entities\Role;

class RequiredForRoleValidationTest extends \TestCase
{

    public function testValidationFailsIfAdmin()
    {
        $this->beLoggedInAsAdmin();
        $validator = \Validator::make(
            [],
            ['input' => 'required_for_role:' . Role::ADMIN]
        );
        $this->assertFalse($validator->passes());
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