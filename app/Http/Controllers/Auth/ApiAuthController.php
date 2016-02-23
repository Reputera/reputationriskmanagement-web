<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiAuthController extends Controller
{
    /**
     * @api {post} /login Login
     * @apiName UserLogin
     * @apiGroup Users
     * @apiParam {String} email The email of the user to login.
     * @apiParam {String} password the plain text password to use for login.
     * @apiDescription Logs a user in with the given credentials.
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data": {"token": "SomeLongTokenString"},
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     */
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->unauthorizedResponse('Invalid credentials');
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->errorResponse();
        }

        // all good so return the token
        return $this->respondWithArray(compact('token'));
    }
}
