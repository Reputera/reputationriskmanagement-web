<?php

namespace Features\Login;

class RequesterMustHaveAdiKeyToLoginTest extends \TestCase
{
    public function testRequesterMustHaveAdiKeyToLogin()
    {
        $this->apiCall('post', 'login', []);
        $this->assertJsonResponseNotAuthorized();
    }
}
