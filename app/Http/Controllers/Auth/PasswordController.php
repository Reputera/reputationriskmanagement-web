<?php

namespace App\Http\Controllers\Auth;

use App\Entities\Status;
use App\Entities\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    // The path to redirect to after login.
    protected $redirectTo;

    /**
     * Create a new password controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['forceReset', 'showResetForm', 'reset']]);
        $this->redirectTo = route('admin.landing');
    }

    /**
     * Get the response for after a successful password reset.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetSuccessResponse($response)
    {
        $user = User::whereEmail(RequestFacade::get('email'))->first();
        $user->status = Status::ENABLED;
        $user->save();

        if ($user->isAdmin()) {
            return redirect($this->redirectPath())->with('status', trans($response));
        }
        return view('auth.passwords.successfullyReset', ['email' => $user->email]);
    }

    public function forceReset(Request $request)
    {
        $token = Password::broker($this->getBroker())->createToken($request->user());

        return redirect()->route('password.reset.get', [
            $token,
            'email' => $request->user()->email
        ]);
    }
}
