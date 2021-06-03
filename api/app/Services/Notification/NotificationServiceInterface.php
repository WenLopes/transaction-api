<?php

namespace App\Services\Notification;

use App\Models\Notification\Notification;

interface NotificationServiceInterface
{

    public function send(Notification $notification): bool;
}
