<?php

namespace App\Exceptions;

use App\Exceptions\Generic\GenericException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        if (!config('app.debug')) {
            $this->renderable(function (\Exception $e) {

                $exception = new GenericException($e->getMessage());
                $exception->report();
                return response()->json([
                    'message' => $exception->message()
                ], $exception->httpCode());
            });
        }
    }
}
