<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

abstract class BaseException extends \Exception implements BaseExceptionInterface {
    
    protected $fatalErrorCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * Exception Technical Code
     * @return string
     */
    public function errorCode(): int
    {
        return $this->getCode();
    }

    /**
     * Exception Technical Message
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getMessage();
    }

    /**
     * Exception code
     * @return int
     */
    public function httpCode(): int
    {
        return $this->fatalErrorCode;
    }

    public function log() : void
    {
        $exception = get_class($this);
        Log::channel('exception')->error("Exception: {$exception} | Message: {$this->errorMessage()}");
    }

    /**
     * Exception Message
     * @return string
     */
    abstract public function message(): string;
}