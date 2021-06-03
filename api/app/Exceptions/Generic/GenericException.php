<?php

namespace App\Exceptions\Generic;

use App\Exceptions\BaseException;

class GenericException extends BaseException
{

    public function message(): string
    {
        return 'Unmapped error';
    }
}
