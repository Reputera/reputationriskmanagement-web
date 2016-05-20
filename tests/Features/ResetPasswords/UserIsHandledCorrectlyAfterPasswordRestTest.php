<?php

namespace Tests\Features\ResetPasswords;

use App\Entities\Role;
use App\Entities\Status;
use App\Entities\User;
use Illuminate\Support\Facades\Password;

class UserIsHandledCorrectlyAfterPasswordRestTest extends \TestCase
{
    protected $user = null;

    protected $credentials = null;

    public function tearDown()
    {
        $this->user = null;
        $this->credentials = null;
        parent::tearDown();
    }

    public function test_when_user_resets_password_status_is_properly_updated()
    {
        $this->setupUserAndCredentials();
        $this->callPasswordReset();

        $this->seeInDatabase('users', ['id' => $this->user->id, 'status' => Status::ENABLED]);
    }

    public function test_when_non_admin_user_resets_password_they_are_show_web_page()
    {
        $this->setupUserAndCredentials();
        $this->callPasswordReset();

        $viewHtml = $this->response->getContent();

        $this->assertContains('<h3 >Password Successfully Rest For '.$this->user->email.'</h3>', $viewHtml);
        $this->assertContains('You can sign into the app with you new password now.', $viewHtml);
    }

    public function test_when_admin_user_resets_password_they_redirected()
    {
        $this->setupUserAndCredentials(['role' => Role::ADMIN]);
        $this->callPasswordReset();

        $this->assertRedirectedToRoute('admin.landing');
    }

    protected function setupUserAndCredentials(array $userAttributes = [])
    {
        $userAttributes = array_merge($userAttributes, ['status' => Status::EMAIL_NOT_CHANGED]);
        $this->user = factory(User::class)->create($userAttributes);

        $this->credentials = [
            'email' => $this->user->email,
            'password' => $this->user->password,
            'password_confirmation' => $this->user->password,
            'token' => '123456789'
        ];

        Password::shouldReceive('broker')->once()->with('')->andReturnSelf();
        Password::shouldReceive('reset')->once()->with($this->credentials, \Mockery::type(\Closure::class))
            ->andReturn(Password::PASSWORD_RESET);
    }

    protected function callPasswordReset()
    {
        $this->call('POST', 'password/reset', $this->credentials);
    }
}
