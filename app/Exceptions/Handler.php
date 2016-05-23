<?php

namespace App\Exceptions;

use App\Http\Traits\ErrorResponses;
use App\Http\Traits\IsApiRequestChecker;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    use ErrorResponses, IsApiRequestChecker;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        NotFoundHttpException::class,
        TokenExpiredException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof TokenExpiredException) {
            return $this->unauthorizedResponse('Session expired');
        } elseif ($e instanceof FileNotFoundException) {
            return $this->notFoundResponse('Not found');
        } elseif ($e instanceof TokenInvalidException) {
            return $this->unauthorizedResponse(['token_invalid']);
        } elseif ($e instanceof TokenMismatchException) {
            return redirect()->route('get.login')
                ->with('error_message', 'Your token has expired. Please re-try your request.');
        } elseif ($this->isApiRequest($request)) {
            if ($e instanceof ValidationException) {
                return $this->unprocessableEntityResponse($e);
            } elseif ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                return $this->notFoundResponse();
            }
        }

        return parent::render($request, $e);
    }
}
