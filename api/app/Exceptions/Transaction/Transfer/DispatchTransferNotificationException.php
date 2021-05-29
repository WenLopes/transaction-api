<?php

namespace App\Exceptions\Transaction\Transfer;

use App\Exceptions\BaseException;
use App\Exceptions\BaseExceptionInterface;

class DispatchTransferNotificationException extends BaseException implements BaseExceptionInterface {
    
    public function message() : string
    {
        return 'Um erro ocorreu ao despachar notificações relacionadas a transferência para os usuários';
    }

}