<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Transformers\Company\CompanyTransformer;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiAuthController extends ApiController
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
     *      "data": {
     *          "token": "SomeLongTokenString",
     *          "company": {
     *              "id": 1,
     *              "name": "Company Name",
     *              "entity_id": "AV4DTB",
     *              "max_alert_threshold": 90,
     *              "min_alert_threshold": -90
     *          }
     *      },
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
        return $this->respondWithArray([
            'token' => $token,
            'company' => auth()->user()->company ? $this->transform(auth()->user()->company, new CompanyTransformer()) : ''
        ]);
    }

    /**
     * @api {post} /logout Logout
     * @apiName UserLogout
     * @apiGroup Users
     * @apiDescription Logs a user out.
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data": {
     *      },
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     */
    public function logout()
    {
        auth()->guard()->logout();
        return $this->respondWithArray([]);
    }
}
