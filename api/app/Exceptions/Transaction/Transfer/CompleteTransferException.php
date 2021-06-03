<?php

namespace App\Exceptions\Transaction\Transfer;

use App\Exceptions\BaseException;

class CompleteTransferException extends BaseException
{

    public function message(): string
    {
        return 'An error occurred while completing the transfer';
    }
}
