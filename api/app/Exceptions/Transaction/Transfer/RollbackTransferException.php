<?php

namespace App\Exceptions\Transaction\Transfer;

use App\Exceptions\BaseException;
use App\Exceptions\BaseExceptionInterface;

class RollbackTransferException extends BaseException implements BaseExceptionInterface {
    
    public function message() : string
    {
        return 'An error occurred while reversing the transfer';
    }

}