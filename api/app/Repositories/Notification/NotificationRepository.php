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
    public function setAsDispatched( int $notificationId ) : bool {
        return $this->update($notificationId, [
            'notification_status_id' => config('constants.notification.status.DISPATCHED'),
            'processed_at' => now()
        ]);
    }

    /**
     * Set notification status as error
     * @param int $notificationId
     * @return bool
     */
    public function setAsError( int $notificationId ) : bool {
        return $this->update($notificationId, [
            'notification_status_id' => config('constants.notification.status.ERROR'),
            'processed_at' => now()
        ]);
    }
}