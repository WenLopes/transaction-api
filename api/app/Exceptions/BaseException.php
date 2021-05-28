<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;

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

    /**
     * Exception Message
     * @return string
     */
    abstract public function message(): string;
}