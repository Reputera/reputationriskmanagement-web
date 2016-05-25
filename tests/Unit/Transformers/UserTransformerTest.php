<?php

namespace Tests\Unit\Transformers;

use App\Entities\User;
use App\Transformers\User\UserTransformer;

class UserTransformerTest extends \TestCase
{
    public function testPresentWithPhoneNumber()
    {
        $user = factory(User::class)->create(['phone_number' => 1234567891, 'phone_number_extension' => 123]);

        $transformedData = (new UserTransformer())->transform($user);
        $this->assertEquals($this->getExpectedResults($user), $transformedData);
    }

    public function testPresentWithoutPhoneNumber()
    {
        $user = factory(User::class)->create();

        $transformedData = (new UserTransformer())->transform($user);
        $this->assertEquals($this->getExpectedResults($user), $transformedData);
    }

    public function testAdminGetsAdditionalFields()
    {
        $user = $this->beLoggedInAsAdmin(['phone_number' => 1234567891, 'phone_number_extension' => 123]);
        
        $transformedData = (new UserTransformer())->transform($user);
        $this->assertEquals($this->getExpectedResults($user), $transformedData);
    }

    protected function getExpectedResults(User $user)
    {
        $phoneNumber = null;
        if ($user->phone_number) {
            $phoneNumber = "(".substr($user->phone_number, 0, 3).") ".
                substr($user->phone_number, 3, 3)."-".substr($user->phone_number, 6);
        }

        $returnArray = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone_number' => $phoneNumber,
            'phone_number_extension' => $user->phone_number_extension
        ];

        if ($user->isAdmin()) {
            $returnArray['id'] = $user->id;
            $returnArray['updated_at'] = (string) $user->updated_at;
            $returnArray['deleted_at'] = (string) $user->deleted_at;
        }

        return $returnArray;
    }
}
