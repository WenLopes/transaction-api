<?php

namespace App\Exceptions\Transaction\Transfer;

use App\Exceptions\BaseException;
use App\Exceptions\BaseExceptionInterface;

class DispatchTransferNotificationException extends BaseException {
    
    public function message() : string
    {
        return 'An error occurred while dispatching notifications to the processing service';
    }

}