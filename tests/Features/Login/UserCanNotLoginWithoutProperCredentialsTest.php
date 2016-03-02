<?php

namespace Tests\Features\Login;

use App\Entities\ApiKey;
use App\Entities\User;

class UserCanNotLoginWithoutProperCredentialsTest extends \TestCase
{
    use \ApiLoginTrait;

    public function testCannotLoginWithoutValidCredentials()
    {
        $errorMessage = 'Invalid credentials';
        $password = 'password';
        /** @var User $user */
        $user = factory(User::class)->create(['password' => bcrypt($password)]);
        $apiKey = factory(ApiKey::class)->create();

        $this->apiLoginCall([], $apiKey->username, $apiKey->key);
        $this->assertJsonResponseNotAuthorized($errorMessage);

        $this->apiLoginCall(['email' => $user->email], $apiKey->username, $apiKey->key);
        $this->assertJsonResponseNotAuthorized($errorMessage);

        $this->apiLoginCall(['password' => $password], $apiKey->username, $apiKey->key);
        $this->assertJsonResponseNotAuthorized($errorMessage);

        $this->apiLoginCall(['email' => 'badEmail', 'password' => $password], $apiKey->username, $apiKey->key);
        $this->assertJsonResponseNotAuthorized($errorMessage);

        $this->apiLoginCall(['email' => $user->email, 'password' => 'badPassword'], $apiKey->username, $apiKey->key);
        $this->assertJsonResponseNotAuthorized($errorMessage);
    }
}
