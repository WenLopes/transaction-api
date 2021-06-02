<?php

namespace Tests\Unit\Services\Transaction\Transfer;

use App\Events\Transaction\Transfer\TransferFailed;
use App\Exceptions\Transaction\Transfer\RollbackTransferException;
use App\Models\Transaction\Transaction;
use App\Models\User\User;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Transaction\Transfer\RollbackTransferService;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RollbackTransferServiceTest extends TestCase
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
    public function test_should_not_finish_when_transaction_status_fail_to_change_to_error()
    {
        $this->expectException(RollbackTransferException::class);
        $this->expectExceptionMessage("Error setting transaction status as failed");

        $this->mockTransactionRepo->method('setAsError')->willReturn(false);

        $rollbackTransferService = new RollbackTransferService(
            $this->mockTransactionRepo,
            $this->mockUserRepo
        );

        $rollbackTransferService->handle($this->transaction->id);
    }

   /**
    * @test
    */
    public function test_should_not_finish_when_transaction_value_is_not_added_to_the_payer_balance()
    {
        $this->expectException(RollbackTransferException::class);
        $this->expectExceptionMessage("Error adding value to payer balance");

        $this->mockTransactionRepo->method('setAsError')->willReturn(true);
        $this->mockTransactionRepo->method('findById')->willReturn( $this->transaction );
        $this->mockUserRepo->method('addBalance')->willReturn(false);

        $rollbackTransferService = new RollbackTransferService(
            $this->mockTransactionRepo,
            $this->mockUserRepo
        );

        $rollbackTransferService->handle($this->transaction->id);
    }


    /**
     * @test
     */
    public function test_transfer_failure_event_should_be_triggered()
    {
        Event::fake();

        $this->mockTransactionRepo->method('setAsError')->willReturn(true);
        $this->mockTransactionRepo->method('findById')->willReturn( $this->transaction );
        $this->mockUserRepo->method('addBalance')->willReturn(true);

        $rollbackTransferService = new RollbackTransferService(
            $this->mockTransactionRepo,
            $this->mockUserRepo
        );

        $rollbackTransferService->handle($this->transaction->id);

        Event::assertDispatched(TransferFailed::class);
    }

}
