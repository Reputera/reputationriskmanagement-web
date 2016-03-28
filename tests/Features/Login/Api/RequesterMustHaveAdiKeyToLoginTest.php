<?php

namespace Tests\Features\Login\Api;

class RequesterMustHaveAdiKeyToLoginTest extends \TestCase
{
    public function testRequesterMustHaveAdiKeyToLogin()
    {
        $this->json('POST', 'api/login', [])
            ->seeJson([
                'message' => 'Not authorized',
                'status_code' => 401
            ]);
    }
}
