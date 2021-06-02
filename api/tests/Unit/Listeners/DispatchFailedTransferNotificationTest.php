<?php 

namespace Tests\Unit\Listeners;

use App\Events\Transaction\Transfer\TransferFailed;
use App\Jobs\Notification\SendNotificationJob;
use App\Listeners\Transaction\Transfer\DispatchFailedTransferNotification;
use App\Models\Transaction\Transaction;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;

class DispatchFailedTransferNotificationTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @test
     */
    public function test_should_dispatch_payer_notification()
    {
        Queue::fake();

        $transactionError = Transaction::factory()->create([
            'transaction_status_id' => config('constants.transaction.status.ERROR')
        ]);

        $event = $this->createMock(TransferFailed::class);
        $event->method('getTransaction')->willReturn($transactionError);

        /** @var DispatchFailedTransferNotification */        
        $listener = app(DispatchFailedTransferNotification::class);
        $listener->handle($event);
        
        $this->assertDatabaseHas('notifications', [
            'user_id' => $transactionError->payer_id,
            'subject' => 'Error while transferring',
            'notification_status_id' => config('constants.notification.status.WAITING')
        ]);
        Queue::assertPushed(SendNotificationJob::class);
    }
}