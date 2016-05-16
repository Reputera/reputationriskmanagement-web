<?php

namespace Tests\Features\ResetPasswords;

use App\Entities\ApiKey;
use App\Entities\User;

class UserCanResetPasswordFromApiTest extends \TestCase
{
    use \ApiAccessTrait;

    public function testResettingPasswordFromApi()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $apiKey = factory(ApiKey::class)->create();

        $this->apiResetPasswordCall(['email' => $user->email], $apiKey->username, $apiKey->key);

        $this->assertJsonResponseOkAndFormattedProperly();
        $this->assertJsonResponseHasDataKey('success');
        $data = $this->response->getData(true);
        $this->assertEquals($data['data']['success'], 'Email successfully sent to '.$user->email);
    }
}
