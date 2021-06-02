<?php 

namespace Tests\Unit\Listeners;

use App\Events\Transaction\Transfer\TransferSuccess;
use App\Jobs\Notification\SendNotificationJob;
use App\Listeners\Transaction\Transfer\DispatchSuccessTransferNotification;
use App\Models\Transaction\Transaction;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;

class DispatchSuccessTransferNotificationTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @test
     */
    public function test_should_dispatch_payer_and_payee_notifications()
    {
        Queue::fake();

        $transactionSuccess = Transaction::factory()->create([
            'transaction_status_id' => config('constants.transaction.status.SUCCESS')
        ]);

        $event = $this->createMock(TransferSuccess::class);
        $event->method('getTransaction')->willReturn($transactionSuccess);

        /** @var DispatchSuccessTransferNotification */        
        $listener = app(DispatchSuccessTransferNotification::class);
        $listener->handle($event);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $transactionSuccess->payer_id,
            'subject' => 'Transfer successful!',
            'notification_status_id' => config('constants.notification.status.WAITING')
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $transactionSuccess->payee_id,
            'subject' => 'You received a transfer!',
            'notification_status_id' => config('constants.notification.status.WAITING')
        ]);

        Queue::assertPushed(SendNotificationJob::class);
    }
}