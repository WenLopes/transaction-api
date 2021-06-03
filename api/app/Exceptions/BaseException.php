<?php

namespace App\Exceptions;

use App\Exceptions\Interfaces\RenderableExceptionInterface;
use App\Exceptions\Interfaces\ReportableExceptionInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

abstract class BaseException extends \Exception implements
    RenderableExceptionInterface,
    ReportableExceptionInterface
{

    protected $fatalErrorCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * Exception Technical Message
     * @return string
     */
    public function exceptionMessage(): string
    {
        return $this->getMessage();
    }

    /**
     * Report exception
     * @return void
     */
    public function report(): void
    {
        $exception = get_class($this);
        Log::channel('exception')->error("Exception: {$exception} | Message: {$this->exceptionMessage()}");
    }

    /**
     * Http code
     * @return int
     */
    public function httpCode(): int
    {
        return $this->fatalErrorCode;
    }

    /**
     * Render exception
     */
    public function render()
    {
        if (! config('app.debug')) {
            return response()->json(['message' => $this->message() ], $this->httpCode());
        }
    }

    /**
     * Exception friendly message
     * @return string
     */
    abstract public function message(): string;
}
