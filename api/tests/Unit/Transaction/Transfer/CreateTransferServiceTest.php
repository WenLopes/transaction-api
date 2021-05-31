<?php

namespace Tests\Unit\Transaction\Transfer;

use App\Exceptions\Transaction\Transfer\CreateTransferException;
use App\Jobs\Transaction\Transfer\ProcessTransferJob;
use App\Models\Transaction\Transaction;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Transaction\Transfer\CreateTransferService;
use App\Services\Transaction\Transfer\CreateTransferServiceInterface;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;

class CreateTransferServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $userRepo;    
    private $transactionRepo;
    private $payer;
    private $payee;

    protected function setUp() : void
    {
        parent::setUp();

        $this->userRepo = $this->createMock(UserRepositoryInterface::class);

        $this->transactionRepo = $this->createMock(TransactionRepositoryInterface::class);

        $this->payer = \App\Models\User\User::factory()->create([
            'is_seller' => false,
            'balance' => 1000
        ]);

        $this->payee = \App\Models\User\User::factory()->create([
            'is_seller' => true
        ]);
    }

    /** 
     * @test 
     */
    public function test_should_not_finish_when_transaction_is_not_created_in_database()
    {
        $this->expectException(CreateTransferException::class);
        $this->expectExceptionMessage('An error occurred while inserting transfer data on database');

        $this->transactionRepo->method('create')->willReturn(null);

        $createTransferService = new CreateTransferService(
            $this->transactionRepo,
            $this->userRepo
        );

        /** @var CreateTransferServiceInterface*/
        $createTransferService->handle($this->payee->id, $this->payer->id, 10);
    }

    /** 
     * @test 
     */
    public function test_should_not_finish_when_subtract_user_balance_fail()
    {
        $this->expectException(CreateTransferException::class);
        $this->expectExceptionMessage('The user has no balance to proceed');

        $this->transactionRepo->method('create')->willReturn(
            Transaction::create([
                'payee_id' => $this->payee->id,
                'payer_id' => $this->payer->id,
                'value' => 10
            ])
        );

        $this->userRepo->method('subtractBalance')->willReturn(false);

        $createTransferService = new CreateTransferService(
            $this->transactionRepo,
            $this->userRepo
        );

        /** @var CreateTransferServiceInterface*/
        $createTransferService->handle($this->payee->id, $this->payer->id, 10);
    }

    /**
     * @test
    */
    public function test_should_create_transfer_job_process_when_finished()
    {
        Queue::fake();
        $this->transactionRepo->method('create')->willReturn(
            Transaction::create([
                'payee_id' => $this->payee->id,
                'payer_id' => $this->payer->id,
                'value' => 10
            ])
        );
        $this->userRepo->method('subtractBalance')->willReturn(true);

        $createTransferService = new CreateTransferService(
            $this->transactionRepo,
            $this->userRepo
        );

        /** @var CreateTransferServiceInterface*/
        $createTransferService->handle($this->payee->id, $this->payer->id, 10);
        Queue::assertPushed(ProcessTransferJob::class);
    }

    /**
     * @test
     */
    public function test_should_return_valid_transaction_when_finished()
    {
        $this->transactionRepo->method('create')->willReturn(
            Transaction::create([
                'payee_id' => $this->payee->id,
                'payer_id' => $this->payer->id,
                'value' => 10
            ])
        );

        $this->userRepo->method('subtractBalance')->willReturn(true);

        $createTransferService = new CreateTransferService(
            $this->transactionRepo,
            $this->userRepo
        );

        /** @var CreateTransferServiceInterface*/
        $transaction = $createTransferService->handle($this->payee->id, $this->payer->id, 10);

        $this->assertEquals(10, $transaction->value);
        $this->assertEquals($this->payee->id, $transaction->payee_id);
        $this->assertEquals($this->payer->id, $transaction->payer_id);
    }
}
