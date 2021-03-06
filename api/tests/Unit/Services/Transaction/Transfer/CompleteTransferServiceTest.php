<?php

namespace Tests\Unit\Services\Transaction\Transfer;

use App\Events\Transaction\Transfer\TransferSuccess;
use App\Exceptions\Transaction\Transfer\CompleteTransferException;
use App\Models\Transaction\Transaction;
use App\Models\User\User;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Transaction\Transfer\CompleteTransferService;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompleteTransferServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $mockUserRepo;    
    private $mockTransactionRepo;
    private $payer;
    private $payee;
    private $transaction;

    protected function setUp() : void
    {
        parent::setUp();

        $this->mockUserRepo = $this->createMock(UserRepositoryInterface::class);

        $this->mockTransactionRepo = $this->createMock(TransactionRepositoryInterface::class);

        $this->payer = User::factory()->create([
            'is_seller' => false,
            'balance' => 1000,
            'active' => true
        ]);

        $this->payee = User::factory()->create([
            'is_seller' => true,
            'active' => true
        ]);

        $this->transaction = Transaction::factory()->create([
            'payer_id'  => $this->payer->id,
            'payee_id'  => $this->payee->id,
            'value'     => 100,
            'transaction_status_id' => config('constants.transaction.status.WAITING')
        ]);
    }

    /**
     * @test
     */
    public function test_should_not_complete_transfer_if_transaction_already_processed()
    {
        $transactionMock = $this->createMock(Transaction::class);
        $transactionMock->method('alreadyProcessed')->willReturn(true);
        $completeTransferService = new CompleteTransferService(
            $this->mockTransactionRepo,
            $this->mockUserRepo
        );
        $this->assertFalse($completeTransferService->handleCompleteTransfer($transactionMock));
    }

    /**
    * @test
    */
    public function test_should_not_complete_transfer_when_transaction_status_fail_to_change_to_success()
    {
        $this->expectException(CompleteTransferException::class);
        $this->expectExceptionMessage("Error setting transaction status as complete");

        $this->mockTransactionRepo->method('setAsSuccess')->willReturn(false);

        $completeTransferService = new CompleteTransferService(
            $this->mockTransactionRepo,
            $this->mockUserRepo
        );

        $completeTransferService->handleCompleteTransfer($this->transaction);
    }

    /**
    * @test
    */
    public function test_should_not_complete_transfer_when_transaction_value_is_not_added_to_the_payee_balance()
    {
        $this->expectException(CompleteTransferException::class);
        $this->expectExceptionMessage("Error adding value to payee balance");

        $this->mockTransactionRepo->method('setAsSuccess')->willReturn(true);
        $this->mockTransactionRepo->method('findById')->willReturn( $this->transaction );
        $this->mockUserRepo->method('addBalance')->willReturn(false);

        $completeTransferService = new CompleteTransferService(
            $this->mockTransactionRepo,
            $this->mockUserRepo
        );

        $completeTransferService->handleCompleteTransfer($this->transaction);
    }

    /**
     * @test
     */
    public function test_transfer_success_event_should_be_triggered()
    {
        Event::fake();

        $this->mockTransactionRepo->method('setAsSuccess')->willReturn(true);
        $this->mockTransactionRepo->method('findById')->willReturn( $this->transaction );
        $this->mockUserRepo->method('addBalance')->willReturn(true);

        $completeTransferService = new CompleteTransferService(
            $this->mockTransactionRepo,
            $this->mockUserRepo
        );

        $completeTransferService->handleCompleteTransfer($this->transaction);

        Event::assertDispatched(TransferSuccess::class);
    }
    
    /**
     * @test
     */
    public function test_payee_should_receive_transaction_value()
    {
        $oldPayeeBalance = $this->payee->balance;

        /** @var CompleteTransferService */
        $completeTransferService = app(CompleteTransferService::class);
        $completeTransferService->handleCompleteTransfer($this->transaction);

        $this->assertNotEquals($oldPayeeBalance, $this->payee->fresh()->balance);
        $this->assertEquals( 
            ($oldPayeeBalance + $this->transaction->value), 
            $this->payee->fresh()->balance
        );
    }
}
