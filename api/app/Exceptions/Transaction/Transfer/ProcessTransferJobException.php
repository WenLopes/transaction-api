<?php

namespace App\Exceptions\Transaction\Transfer;

use App\Exceptions\BaseException;
use App\Exceptions\BaseExceptionInterface;

class ProcessTransferJobException extends BaseException implements BaseExceptionInterface {
    
    public function message() : string
    {
        return 'An error occurred while processing and consulting the transfer authorization service';
    }

}