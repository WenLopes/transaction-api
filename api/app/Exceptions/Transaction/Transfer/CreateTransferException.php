<?php

namespace App\Exceptions\Transaction\Transfer;

use App\Exceptions\BaseException;
use App\Exceptions\BaseExceptionInterface;

class CreateTransferException extends BaseException implements BaseExceptionInterface {
    
    public function message() : string
    {
        return 'An error occurred while creating the transfer';
    }

}