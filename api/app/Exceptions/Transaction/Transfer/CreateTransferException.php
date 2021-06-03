<?php

namespace App\Exceptions\Transaction\Transfer;

use App\Exceptions\BaseException;

class CreateTransferException extends BaseException
{

    public function message(): string
    {
        return 'An error occurred while creating the transfer';
    }
}
