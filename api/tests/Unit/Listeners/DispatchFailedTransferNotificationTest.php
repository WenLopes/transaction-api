<?php 

namespace Tests\Unit\Listeners;

use App\Events\Transaction\Transfer\TransferSuccess;
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

        $event = $this->createMock(TransferSuccess::class);
        $event->method('getTransaction')->willReturn($transactionError);

        /** @var DispatchFailedTransferNotification */        
        $listener = app(DispatchFailedTransferNotification::class);
        $listener->handle($event);

        Queue::assertPushed(SendNotificationJob::class);
    }
}