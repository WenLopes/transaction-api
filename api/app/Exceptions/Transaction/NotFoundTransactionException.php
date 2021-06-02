<?php

namespace App\Exceptions\Transaction;

use App\Exceptions\BaseException;
use App\Exceptions\BaseExceptionInterface;

class NotFoundTransactionException extends BaseException {
    
    /**
     * Exception code
     * @return int
     */
    public function httpCode(): int
    {
        return 404;
    }

    public function message() : string
    {
        return 'Transaction not found';
    }

}