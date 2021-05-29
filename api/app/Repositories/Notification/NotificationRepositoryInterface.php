<?php 

namespace App\Repositories\Notification;

use App\Models\Notification\Notification;
use App\Repositories\BaseRepositoryInterface;

interface NotificationRepositoryInterface extends BaseRepositoryInterface {

    public function setAsDispatched( int $notificationId ) : ?Notification;

    public function setAsError( int $notificationId ) : ?Notification;

}