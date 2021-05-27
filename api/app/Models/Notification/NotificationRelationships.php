<?php 

namespace App\Models\Notification;

trait NotificationRelationships {

    public function status()
    {
        return $this->hasOne(\App\Models\Notification\Notification::class, 'id', 'notification_status_id');
    }
}