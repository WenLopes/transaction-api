<?php 

namespace App\Services\Notification;

interface NotificationServiceInterface {

    public function send(int $notificationId): bool;

}