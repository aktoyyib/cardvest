<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Throwable;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        
        $this->renderable(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'message' => 'Entry for Resource not found'], 404);
        });

        $this->renderable(function (RequestException $e, $request) {
            return response()->json([
                'message' => 'Could not make connection to the external server'], 500);
        });

        $this->renderable(function (ConnectionException $e, $request) {
            return response()->json([
                'message' => 'Could not make connection to the external server. Check your internet connection.'], 500);
        });
        
    }
}
