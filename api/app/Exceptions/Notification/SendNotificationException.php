<?php

namespace App\Exceptions\Notification;

use App\Exceptions\BaseException;
use App\Exceptions\BaseExceptionInterface;

class SendNotificationException extends BaseException implements BaseExceptionInterface {
    
    public function message() : string
    {
        return 'Um erro ocorreu ao enviar a notificação';
    }

}