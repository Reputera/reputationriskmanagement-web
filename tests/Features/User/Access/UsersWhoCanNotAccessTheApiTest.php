<?php

namespace Tests\Features\User\Access;

use App\Entities\Status;

class UsersWhoCanNotAccessTheApiTest extends \TestCase
{
    public function test_users_who_have_not_changed_their_password_can_not_access_the_api()
    {
        \Route::get('api/testing', function () {
            echo 'hello';
        })->middleware(['status:'.Status::ENABLED]);

        $this->beLoggedInAsAdmin(['status' => Status::EMAIL_NOT_CHANGED]);

        $this->apiCall('get', 'testing');

        $jsonData = $this->response->getData(true);

        $this->assertResponseStatus(401);
        $this->assertArrayHasKey('message', $jsonData);
        $this->assertEquals('You must change your email to have access to the system.', $jsonData['message']);
    }

    public function test_users_who_are_disabled_can_not_access_the_api()
    {
        \Route::get('api/testing', function () {
            echo 'hello';
        })->middleware(['status:'.Status::ENABLED]);

        $this->beLoggedInAsAdmin(['status' => Status::DISABLED]);

        $this->apiCall('get', 'testing');

        $jsonData = $this->response->getData(true);

        $this->assertResponseStatus(401);
        $this->assertArrayHasKey('message', $jsonData);
        $this->assertEquals('Not authorized', $jsonData['message']);
    }
}
