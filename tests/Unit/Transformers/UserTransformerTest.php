<?php

namespace Tests\Unit\Transformers;

use App\Entities\User;
use App\Transformers\User\UserTransformer;

class UserTransformerTest extends \TestCase
{
    public function testPresentWithPhoneNumber()
    {
        $user = factory(User::class)->create(['phone_number' => 1234567891, 'phone_number_extension' => 123]);

        $expectedResults = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone_number' => '(123) 456-7891',
            'phone_number_extension' => $user->phone_number_extension
        ];

        $transformedData = (new UserTransformer())->transform($user);
        $this->assertEquals($expectedResults, $transformedData);
    }

    public function testPresentWithoutPhoneNumber()
    {
        $user = factory(User::class)->create();

        $expectedResults = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone_number' => $user->phone_number,
            'phone_number_extension' => $user->phone_number_extension
        ];

        $transformedData = (new UserTransformer())->transform($user);
        $this->assertEquals($expectedResults, $transformedData);
    }
}
