<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Request;

class ApiPasswordController extends ApiController
{
    use ResetsPasswords;

    /**
     * @api {post} /password/reset/ Reset Password
     * @apiName ResetPassword
     * @apiDescription Starts password reset process, and sends mail to user to continue.
     * @apiGroup Users
     * @apiParam {String} email Email of user requesting password reset.
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data": {
     *          "success": "Email successfully sent to email@email.com"
     *      },
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     */
    /**
     * Get the response for after the reset link has been successfully sent.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSendResetLinkEmailSuccessResponse($response)
    {
        return $this->respondWithArray(['success' =>'Email successfully sent to ' . Request::get('email')]);
    }
}
