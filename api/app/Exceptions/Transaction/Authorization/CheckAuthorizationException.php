<?php

namespace App\Exceptions\Transaction\Authorization;

use App\Exceptions\BaseException;
use App\Exceptions\BaseExceptionInterface;

class CheckAuthorizationException extends BaseException
{

    public function message(): string
    {
        return 'An error occurred while checking transaction authorization';
    }
}
