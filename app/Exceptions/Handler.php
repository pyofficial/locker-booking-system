<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
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

    public function render($request, Throwable $exception): JsonResponse
    {
        // Force JSON response for all API requests
        if ($request->is('api/*')) {
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'errors' => $exception->errors(),
                ], 422);
            }

            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'message' => 'Route not found'
                ], 404);
            }

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], $exception->getCode() ?: 500);
        }

        return parent::render($request, $exception);
    }
}
