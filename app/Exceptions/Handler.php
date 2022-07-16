<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Str;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
    public function render($request, Throwable $exception)
    {
        
        if(Request::expectsJson()){
            $detail = $this->getDetail($exception);
            $error = [];
            $code = $exception->getCode();
            $message = $exception->getMessage();

            if($exception instanceof AccessDeniedHttpException){
                if(!$code){
                    $code = Response::HTTP_FORBIDDEN;
                }
                if(!$message){
                    $message = "You don't have permission to access";
                }
            }
            if($exception instanceof AuthorizationException){
                if(!$code){
                    $code = Response::HTTP_FORBIDDEN;
                }
                if(!$message){
                    $message = "You don't have permission to access";
                }
            }
            if($exception instanceof AuthenticationException){
                if(!$code){
                    $code = Response::HTTP_UNAUTHORIZED;
                }
                if(!$message){
                    $message = "Authorization required";
                }
            }
            if($exception instanceof NotFoundHttpException){
                if(!$code){
                    $code = Response::HTTP_NOT_FOUND;
                }
                if(!$message){
                    $message = "Page was not found";
                }
            }
            if($exception instanceof MethodNotAllowedHttpException){
                if(!$code){
                    $code = Response::HTTP_METHOD_NOT_ALLOWED;
                }
                if(!$message){
                    $message = "This method is not allowed";
                }
            }

            return $this->returnJson($code, $message, $error, $detail);
        }
        return parent::render($request, $exception);
    }

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    private function getDetail($exception){
        return [
            'code' => $exception->getCode(),
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => collect($exception->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ];
    }

    private function returnJson($code, $message, $error, $detail){
        if(empty($message)){
            $message = $message;
        }
        $return = [
            'status'  => false,
            'message' => $message,
            'data'    => null,
            'meta'    => null,
        ];
        if (config('app.debug')){
            $return['error_detail'] = $detail;
        }

        try{
            return response()->json($return, $code);
        }catch (\Throwable $th){
            return response()->json($return, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
