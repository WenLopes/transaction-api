<?php 

namespace App\Repositories\Notification;

use App\Models\Notification\Notification;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface {

    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Notification $model)
    {
        $this->model = $model;
    }

    /**
     * Set notification status as dispatched
     * @param int $notificationId
     * @return bool
     */
    public function setAsDispatched( int $notificationId ) : ?Notification {

        $notification = $this->findById($notificationId);

        if( !$notification ){
            return false;
        }

        $dispatchedStatus = config('constants.notification.status.DISPATCHED');

        $query = $this->update($notificationId, [
            'notification_status_id' => $dispatchedStatus,
            'processed_at' => now()
        ]);

        if( !$query ){
            return false;
        }

        return $this->findById($notificationId);
    }

    /**
     * Set notification status as error
     * @param int $notificationId
     * @return bool
     */
    public function setAsError( int $notificationId ) : ?Notification {

        $notification = $this->findById($notificationId);

        if( !$notification ){
            return false;
        }

        $errorStatus = config('constants.notification.status.ERROR');

        $query = $this->update($notificationId, [
            'notification_status_id' => $errorStatus,
            'processed_at' => now()
        ]);

        if( !$query ){
            return false;
        }

        return $this->findById($notificationId);
    }
}