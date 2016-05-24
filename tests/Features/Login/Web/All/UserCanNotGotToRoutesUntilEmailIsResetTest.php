<?php

namespace Tests\Features\Login\Web\All;

use App\Entities\Status;
use App\Entities\User;
use Illuminate\Support\Facades\Hash;

class UserCanNotGotToRoutesUntilEmailIsResetTest extends \TestCase
{
    public function test_user_must_reset_password_to_access_any_protected_routes()
    {
        $user = $this->beLoggedInAsAdmin(['status' => Status::EMAIL_NOT_CHANGED]);

        $this->visit('/');

        $token = \DB::table('password_resets')->where('email', $user->email)->value('token');

        $this->seePageIs('password/reset/'.$token.'?email='.urlencode($user->email));

        $this->type('Test123', 'password');
        $this->type('Test123', 'password_confirmation');
        $this->press('reset-password');

        $this->seeInDatabase('users', ['id' => $user->id, 'status' => Status::ENABLED]);
        $this->assertTrue(Hash::check('Test123', User::find($user->id)->password));
    }
}
