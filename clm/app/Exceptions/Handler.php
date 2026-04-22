<?php

namespace App\Exceptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    
    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception) && $exception->getStatusCode() == 404) {
            return response()->view('errors.404', [], 404);
        }
        return parent::render($request, $exception);
    }


//     public function render($request, Throwable $exception)
// {
//     dd(auth()->user);
//     if ($exception instanceof AuthenticationException) {
        
//         if (Auth::check()) {
//             return response()->view('errors.authenticated404', [], 404);
//         } else {
//             return response()->view('errors.404', [], 404);
//         }
//     }
//     return parent::render($request, $exception);
// }
}
