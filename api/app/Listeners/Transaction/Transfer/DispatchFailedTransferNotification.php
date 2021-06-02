<?php

namespace App\Listeners\Transaction\Transfer;

use App\Events\Transaction\Transfer\TransferSuccess;
use App\Exceptions\Transaction\Transfer\DispatchTransferNotificationException;
use App\Jobs\Notification\SendNotificationJob;
use App\Models\Notification\Notification;
use App\Models\Transaction\Transaction;
use App\Repositories\Notification\NotificationRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DispatchFailedTransferNotification
{

    /** @var NotificationRepositoryInterface */
    protected $notificationRepo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(NotificationRepositoryInterface $notificationRepo)
    {
        $this->notificationRepo = $notificationRepo;
    }

    /**
     * Handle the event.
     *
     * @param TransferSuccess $event
     * @return void
     */
    public function handle($event) : void
    {
        try {
            $transaction = $event->getTransaction();
            $this->dispatchToPayer($transaction);
        } catch (\Exception $e){
            throw new DispatchTransferNotificationException($e->getMessage());
        }

    }

    /**
     * Dispatches the job responsible for sending notification to transaction payer
     * @var Transaction $transaction
     * @return void
     */
    protected function dispatchToPayer(Transaction $transaction) : void
    {
        $subject = "Error while transferring";
        $content =  "An error occurred while making your transfer in the amount of ".format_brl($transaction->value)." to {$transaction->payee->name}. ". 
                    "But don't worry, the amount will be sent will be credited to your balance.";

        $notification = $this->createNotification( $transaction->payer_id, $subject, $content );
        dispatch( new SendNotificationJob($notification->id) );
    }

    /**
     * Creates the notification in the database
     * @var int $userId
     * @var string $subject
     * @var string $content
     * @return Notification
     */
    protected function createNotification(
        int $userId,
        string $subject,
        string $content
    ) : Notification
    {
        return $this->notificationRepo->create([
            'user_id' => $userId,
            'subject' => $subject,
            'content' => $content
        ]);
    }
}
