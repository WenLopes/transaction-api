<?php

namespace App\Exceptions\Generic;

use App\Exceptions\BaseException;
use App\Exceptions\BaseExceptionInterface;

class GenericException extends BaseException implements BaseExceptionInterface {

    public function message() : string
    {
        return 'Unmapped error';
    }

}