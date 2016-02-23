<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Entities\ApiKey;
use App\Http\Traits\ErrorResponsesTrait;
use Tymon\JWTAuth\Middleware\GetUserFromToken;

class ApiToken extends GetUserFromToken
{
    use ErrorResponsesTrait;

    /**
     * {@inheritDoc}
     */
    public function handle($request, Closure $next)
    {
        // Check JWT's token first
        if ($token = $this->auth->setRequest($request)->getToken()) {
            if (!$this->auth->authenticate($token)) {
                return $this->notFoundResponse('User not found');
            }

            return $next($request);
        }

        // If logged in normally, say for the admin, continue.
        if (Auth::check()) {
            return $next($request);
        }

        /** @var ApiKey $token */
        $token = \DB::table('api_keys')
            ->where('username', $request->server('PHP_AUTH_USER'))
            ->where('key', $request->server('PHP_AUTH_PW'))
            ->first(['deleted_at']);

        if (is_null($token)) {
            return $this->unauthorizedResponse();
        } elseif ($token->deleted_at) {
            return $this->unauthorizedResponse('Your credentials are no longer valid.');
        }

        return $next($request);
    }
}
