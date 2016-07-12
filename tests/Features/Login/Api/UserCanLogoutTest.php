<?php

namespace Tests\Features\Login\Api;

use App\Entities\ApiKey;
use App\Entities\Company;
use App\Entities\User;

class UserCanLogoutTest extends \TestCase
{
    use \ApiAccessTrait;

    public function testUserCanLogout()
    {
        $this->beLoggedInAsUser();
        $this->apiCall('POST', 'vectors');
        $this->assertResponseStatus(200);
        $this->apiCall('POST', 'logout');
        $this->assertJsonResponseOkAndFormattedProperly();
        $this->apiCall('POST', 'vectors');
        $this->assertResponseStatus(401);
    }
}
