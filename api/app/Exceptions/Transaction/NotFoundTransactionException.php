<?php

namespace App\Exceptions\Transaction;

use App\Exceptions\BaseException;
use Illuminate\Http\JsonResponse;

class NotFoundTransactionException extends BaseException {
    
    protected $notFoundCode = JsonResponse::HTTP_NOT_FOUND;

    /**
     * Exception code
     * @return int
     */
    public function httpCode(): int
    {
        return $this->notFoundCode;
    }

    public function message() : string
    {
        return 'Transaction not found';
    }

}