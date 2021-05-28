<?php

namespace App\Exceptions;

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
     * List of mapped exceptions
     * @var array
     */
    protected $mappedExceptions = [
        'App\Exceptions\Transaction\Transfer\CreateTransferException',
        'App\Exceptions\Transaction\Authorization\CheckAuthorizationException'
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // if (!config('app.debug')) {
            $this->renderable(function (\Exception $e ) {

                $exception = $this->getExceptionInstance($e);

                return response()->json([
                    'message' => $exception->message()
                ], $exception->httpCode());

            });
        // }
    }

    /**
     * Get exception instance from mapped exceptions
     * @var \Exception $e 
     * @return BaseExceptionInterface
     */
    protected function getExceptionInstance(\Exception $e) : BaseExceptionInterface
    {
        if( in_array(get_class($e), $this->mappedExceptions) ){
            $exception_class_name = get_class($e);
            $exception = new $exception_class_name($e->getMessage());
            if( $exception instanceof BaseExceptionInterface ){
                return $exception;
            }
        }
        return new \App\Exceptions\Generic\GenericException($e->getMessage());
    }
}
