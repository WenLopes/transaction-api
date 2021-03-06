<?php

namespace App\Services\Notification;

use App\Exceptions\Notification\SendNotificationException;
use App\Models\Notification\Notification;
use App\Repositories\Notification\NotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use DB;
use Exception;

final class NotificationService implements NotificationServiceInterface
{

    /** @var string */
    protected const URL = 'http://o4d9z.mocklab.io/notify';

    /** @var \Illuminate\Http\Client\Response */
    protected $response = null;

    /** @var NotificationRepositoryInterface */
    protected $notificationRepo;

    public function __construct(NotificationRepositoryInterface $notificationRepo)
    {
        $this->notificationRepo = $notificationRepo;
    }

    public function send(Notification $notification): bool
    {
        try {
            DB::beginTransaction();

            if (! $this->notificationRepo->setAsDispatched($notification->id)) {
                throw new Exception("Error setting notification status as dispatched");
            }

            $response = Http::get(self::URL);

            if ($response->status() !== JsonResponse::HTTP_OK) {
                throw new Exception("Notification service response status is invalid");
            }

            if ($response->json()['message'] != 'Success') {
                throw new Exception("Notification service response message is invalid");
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->notificationRepo->setAsError($notification->id);
            throw new SendNotificationException($e->getMessage());
        }
    }
}
