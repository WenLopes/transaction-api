<?php

namespace App\Exceptions\Transaction\Transfer;

use App\Exceptions\BaseException;

class RollbackTransferOnProcessFailedException extends BaseException
{

    public function message(): string
    {
        return 'An error occurred while performing transfer rollback on process job failure';
    }
}
