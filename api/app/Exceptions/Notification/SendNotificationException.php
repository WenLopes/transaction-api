<?php

namespace App\Exceptions\Notification;

use App\Exceptions\BaseException;
use App\Exceptions\BaseExceptionInterface;

class SendNotificationException extends BaseException
{

    public function message(): string
    {
        return 'An error occurred while sending the notification.';
    }
}
