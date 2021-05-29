<?php

namespace App\Listeners\Transaction\Transfer;

use App\Events\Transaction\Transfer\TransferSuccess;
use App\Exceptions\Transaction\Transfer\DispatchTransferNotificationException;
use App\Models\Notification\Notification;
use App\Models\Transaction\Transaction;
use App\Repositories\Notification\NotificationRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DispatchSuccessTransferNotification
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
            $this->dispatchToPayee($transaction);
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
        $subject = "Transferência realizada com sucesso";
        $content = "Sua transferência no valor de {$transaction->value} para {$transaction->payee_id} foi realizada com sucesso";
        $notification = $this->createNotification( $transaction->payer_id, $subject, $content );
    }

    /**
     * Dispatches the job responsible for sending notification to transaction payee
     * @var Transaction $transaction
     * @return void
     */
    protected function dispatchToPayee(Transaction $transaction) : void
    {
        $subject = "Você recebeu uma transferência";
        $content = "Você recebeu uma transferência no valor de {$transaction->payer_id}, no valor de {$transaction->value}";
        $notification = $this->createNotification( $transaction->payee_id, $subject, $content );
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
