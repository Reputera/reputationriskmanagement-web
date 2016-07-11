<?php

namespace Tests\Features\Login\Api;

use App\Entities\ApiKey;
use App\Entities\Company;
use App\Entities\User;

class UserCanLoginTest extends \TestCase
{
    use \ApiAccessTrait;

    public function testUserCanLoginWithValidCredentials()
    {
        $password = 'password';
        $company = factory(Company::class)->create();
        /** @var User $user */
        $user = factory(User::class)->create(['password' => bcrypt($password), 'company_id' => $company->id]);
        $apiKey = factory(ApiKey::class)->create();

        $this->apiLoginCall(['email' => $user->email, 'password' => $password], $apiKey->username, $apiKey->key);

        $this->assertJsonResponseOkAndFormattedProperly();
        $results = $this->response->getData(true)['data'];
        $this->assertJsonResponseHasDataKey('token');
        $this->assertNotEquals('', $results['company']);
    }

    public function testUserCanLoginWithValidCredentialsWithNoCompany()
    {
        $password = 'password';
        /** @var User $user */
        $user = factory(User::class)->create(['password' => bcrypt($password)]);
        $apiKey = factory(ApiKey::class)->create();

        $this->apiLoginCall(['email' => $user->email, 'password' => $password], $apiKey->username, $apiKey->key);

        $this->assertJsonResponseOkAndFormattedProperly();
        $results = $this->response->getData(true)['data'];
        $this->assertEquals('', $results['company']);
        $this->assertJsonResponseHasDataKey('token');
    }
}
