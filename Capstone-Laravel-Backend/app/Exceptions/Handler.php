<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function render($request, Throwable $exception)
    {
        // if then exception is from a validation error
        if ($exception instanceof ValidationException) {
            // return {error: ['error message1', 'error message2']} with a 422 status code
            return response()->json([
                'error' => $exception->validator->errors()->all()
            ], 422);
        }

        // if the user is not authenticated
        if ($exception instanceof AuthenticationException) {
            // return {error: 'Unauthorized'} with a 401 status code
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        return parent::render($request, $exception);
    }
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
