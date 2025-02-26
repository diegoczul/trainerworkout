<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
     *
     * @return void
     */
    public function register(): void
    {
        $this->renderable(function (QueryException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => "Database Exception",
                'data' => $e->errorInfo
            ],400);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],400);
        });
    }
}
