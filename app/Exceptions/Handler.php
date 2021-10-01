<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // This will replace our 404 response with
        // a JSON response.
        if ($exception instanceof ResourceNotFoundException) {
            return response()->json([
                'error' => 'Resource not found'
            ], 404);
        }

        // This will replace our 404 response with
        // a JSON response.
        if ($exception instanceof BadRequestException) {
            return response()->json([
                'error' => 'Bad Request'
            ], 400);
        }

        // This will replace our 500 response with
        // a JSON response.
        if ($exception instanceof \RuntimeException) {
            return response()->json([
                'error' => 'Internal Server Error'
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
