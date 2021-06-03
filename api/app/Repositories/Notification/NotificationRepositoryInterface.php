<?php 

namespace App\Repositories\Notification;

use App\Repositories\BaseRepositoryInterface;

interface NotificationRepositoryInterface extends BaseRepositoryInterface {

    public function setAsDispatched( int $notificationId ) : bool;

    public function setAsError( int $notificationId ) : bool;

}