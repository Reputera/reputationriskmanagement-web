<?php

namespace Tests\Features\Login\Api;

class RequesterMustHaveAdiKeyToLoginTest extends \TestCase
{
    public function testRequesterMustHaveAdiKeyToLogin()
    {
        $this->apiCall('post', 'login', []);
        $this->assertJsonResponseNotAuthorized();
    }
}
