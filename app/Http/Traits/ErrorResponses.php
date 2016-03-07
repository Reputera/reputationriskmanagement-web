<?php

namespace App\Http\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

trait ErrorResponses
{
    /**
     * @apiDefine NotFound
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "message": "Not Found",
     *       "status_code": 404
     *     }
     */
    /**
     * Returns a 404 JSON response with "not found" as the message.
     *
     * @param $message
     * @return JsonResponse
     */
    protected function notFoundResponse($message = 'Not found')
    {
        return new JsonResponse($this->createErrorResponseArray($message, 404), 404);
    }

    /**
     * @apiDefine Error
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 500 Internal Server Error
     *     {
     *       "message": "Internal error", // Can be any message though
     *       "status_code": 500
     *     }
     */
    /**
     * Returns a JSON response with a given message and error code.
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function errorResponse($message = 'Internal error', $code = 500)
    {
        return new JsonResponse($this->createErrorResponseArray($message, $code), $code);
    }

    /**
     * @apiDefine Unauthorized
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401 Unauthorized
     *     {
     *       "message": "Not authorized", // Can be any message though
     *       "status_code": 401
     *     }
     */
    /**
     * Returns a 401 JSON response with "Not authorized" as message.
     *
     * @param string $message The message to return in the response.
     * @return unauthorized
     */
    protected function unauthorizedResponse($message = 'Not authorized')
    {
        return new JsonResponse($this->createErrorResponseArray($message, 401), 401);
    }

    /**
     * Throws an exception when validation fails.
     *
     * @see \Illuminate\Validation\ValidatesWhenResolvedTrait for more info.
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator);
    }

    /**
     * Creates a 422 JSON response - unprocessable entity and puts the validation reasons in the response.
     *
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function unprocessableEntyResponse(ValidationException $exception)
    {
        return $this->unprocessableEntyArrayResponse($exception->validator->errors()->toArray());
    }

    /**
     * @apiDefine UnprocessableEntity
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 422 Unprocessable Entity
     *     {
     *       "message": "Unprocessable Entity",
     *       "status_code": 422,
     *       "errors": "" // JSON encoded array of errors.
     *     }
     */
    /**
     * Creates a 422 JSON response - unprocessable entity and puts the validation reasons in the response.
     *
     * @param array $errors
     * @return JsonResponse
     */
    protected function unprocessableEntyArrayResponse(array $errors)
    {
        return new JsonResponse(
            $this->createErrorResponseArray('Unprocessable Entity', 422, $errors),
            422
        );
    }

    /**
     * Creates a 402 JSON response - payment required and puts the validation reasons in the response.
     *
     * @param array $errors
     * @return JsonResponse
     */
    protected function paymentRequiredArrayResponse(array $errors)
    {
        return new JsonResponse(
            $this->createErrorResponseArray('Payment Required', 402, $errors),
            402
        );
    }

    /**
     * Creates an error response as defined by a passed in handled exception.
     *
     * @param \Exception $exception
     * @return JsonResponse
     */
    protected function handledExceptionResponse(\Exception $exception)
    {
        return new JsonResponse(
            $this->createErrorResponseArray($exception->getMessage(), $exception->getCode()),
            $exception->getCode()
        );
    }

    /**
     * Creates the array used for the JsonResponse
     *
     * @param $message
     * @param $code
     * @param array $errors
     * @return array
     */
    protected function createErrorResponseArray($message, $code, array $errors = [])
    {
        $returnArray = [
            'message' => $message,
            'status_code' => $code,
        ];

        if ($errors) {
            $returnArray['errors'] = $errors;
        }

        return $returnArray;
    }
}
