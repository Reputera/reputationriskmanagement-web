<?php

namespace Tests\Features\Login;

use App\Entities\ApiKey;
use App\Entities\User;

class UserCanLoginTest extends \TestCase
{
    use \ApiLoginTrait;

    public function testUserCanLoginWithValidCredentials()
    {
        $password = 'password';
        /** @var User $user */
        $user = factory(User::class)->create(['password' => bcrypt($password)]);
        $apiKey = factory(ApiKey::class)->create();

        $this->apiLoginCall(['email' => $user->email, 'password' => $password], $apiKey->username, $apiKey->key);

        $this->assertJsonResponseOkAndFormattedProperly();
        $this->assertJsonResponseHasDataKey('token');
    }
}
