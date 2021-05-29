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
}