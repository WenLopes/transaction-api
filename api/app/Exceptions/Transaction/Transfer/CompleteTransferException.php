<?php

namespace App\Exceptions\Transaction\Transfer;

use App\Exceptions\BaseException;
use App\Exceptions\BaseExceptionInterface;

class CompleteTransferException extends BaseException implements BaseExceptionInterface {
    
    public function message() : string
    {
        return 'An error occurred while completing the transfer';
    }

}