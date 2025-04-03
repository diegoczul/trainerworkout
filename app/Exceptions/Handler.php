<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
            Log::driver('query_exceptions_log')->error('Database Query Failed', [
                'query' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'error' => $e->getMessage(),
                'uri' => $request->getRequestUri(),
                'line' => $e->getLine(),
            ]);

            if($request->ajax() || !$request->isMethod('get')){
                return response()->json([
                    'success' => false,
                    'message' => "Database Exception",
                    'data' => $e->getMessage()
                ],400);
            }else{
                abort(500);
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            if($request->ajax() || !$request->isMethod('get')){
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ],400);
            }else{
                abort(500);
            }
        });

//        $this->renderable(function (Exception $e, Request $request) {
//            if($request->ajax() && !$request->isMethod('get')){
//                return response()->json([
//                    'success' => false,
//                    'message' => "Internal Server Error",
//                    'data' => $e->getMessage()
//                ],500);
//            }else{
//                if ($e->getCode() != 404){
//                    Log::error("Exception",[
//                        'error' => $e->getMessage(),
//                        'uri' => $request->getRequestUri(),
//                        'line' => $e->getLine(),
//                    ]);
//                }
//            }
//        });
    }
}
