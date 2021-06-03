<?php

namespace Tests\Unit\Services\Transaction\Transfer;

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

    private $mockUserRepo;    
    private $mockTransactionRepo;
    private $genericUser;
    private $payer;
    private $payee;

    protected function setUp() : void
    {
        parent::setUp();

        $this->mockUserRepo = $this->createMock(UserRepositoryInterface::class);

        $this->mockTransactionRepo = $this->createMock(TransactionRepositoryInterface::class);

        $this->genericUser = \App\Models\User\User::factory()->create([
            'active' => true,
            'balance' => 100
        ]);

        $this->payer = \App\Models\User\User::factory()->create([
            'is_seller' => false,
            'balance' => 1000,
            'active' => true
        ]);

        $this->payee = \App\Models\User\User::factory()->create([
            'is_seller' => true,
            'active' => true
        ]);
    }

    /** 
     * @test 
     */
    public function test_should_not_finish_when_transaction_is_not_created_in_database()
    {
        $this->expectException(CreateTransferException::class);
        $this->expectExceptionMessage('An error occurred while inserting transfer data on database');

        $this->mockTransactionRepo->method('create')->willReturn(null);

        $createTransferService = new CreateTransferService(
            $this->mockTransactionRepo,
            $this->mockUserRepo
        );

        /** @var CreateTransferServiceInterface*/
        $createTransferService->handleCreateTransfer($this->payee->id, $this->payer->id, 10);
    }

    /** 
     * @test 
     */
    public function test_should_not_finish_if_payer_is_a_seller()
    {
        $this->expectException(CreateTransferException::class);
        $this->expectExceptionMessage('The payer cannot be a seller');
    
        //Payee created on setUp is define as a seller
        $sellerUserId = $this->payee->id;
        $this->mockTransactionRepo->method('create')->willReturn(
            Transaction::create([
                'payee_id' => $this->genericUser->id,
                'payer_id' => $sellerUserId,
                'value' => 1
            ])
        );

        $createTransferService = new CreateTransferService(
            $this->mockTransactionRepo,
            $this->mockUserRepo
        );


        /** @var CreateTransferServiceInterface*/
        $createTransferService->handleCreateTransfer($this->genericUser->id, $sellerUserId, 1);
    }

    /** 
     * @test 
     */
    public function test_should_not_finish_when_subtract_payer_balance_fail()
    {
        $this->expectException(CreateTransferException::class);
        $this->expectExceptionMessage('The user has no balance to proceed');

        $this->mockTransactionRepo->method('create')->willReturn(
            Transaction::create([
                'payee_id' => $this->payee->id,
                'payer_id' => $this->payer->id,
                'value' => 10
            ])
        );

        $this->mockUserRepo->method('subtractBalance')->willReturn(false);

        $createTransferService = new CreateTransferService(
            $this->mockTransactionRepo,
            $this->mockUserRepo
        );

        /** @var CreateTransferServiceInterface*/
        $createTransferService->handleCreateTransfer($this->payee->id, $this->payer->id, 10);
    }

    /**
     * @test
    */
    public function test_should_create_transfer_job_process_when_finished()
    {
        Queue::fake();
        $this->mockTransactionRepo->method('create')->willReturn(
            Transaction::create([
                'payee_id' => $this->payee->id,
                'payer_id' => $this->payer->id,
                'value' => 10
            ])
        );
        $this->mockUserRepo->method('subtractBalance')->willReturn(true);

        $createTransferService = new CreateTransferService(
            $this->mockTransactionRepo,
            $this->mockUserRepo
        );

        /** @var CreateTransferServiceInterface*/
        $createTransferService->handleCreateTransfer($this->payee->id, $this->payer->id, 10);
        Queue::assertPushed(ProcessTransferJob::class);
    }

    /**
     * @test
     */
    public function test_payer_should_be_have_transfer_value_debited_from_his_balance()
    {
        $oldPayerBalance = $this->payer->balance;

        /** @var CreateTransferService */
        $createTransferService = app(CreateTransferService::class);
        $transaction = $createTransferService->handleCreateTransfer($this->payee->id, $this->payer->id, 10);

        $this->assertNotEquals($oldPayerBalance, $this->payer->fresh()->balance);
        $this->assertEquals( 
            ($oldPayerBalance - $transaction->value), 
            $this->payer->fresh()->balance
        );
    }

    /**
     * @test
     */
    public function test_should_return_valid_transaction_when_finished()
    {
        $this->mockTransactionRepo->method('create')->willReturn(
            Transaction::create([
                'payee_id' => $this->payee->id,
                'payer_id' => $this->payer->id,
                'value' => 10
            ])
        );

        $this->mockUserRepo->method('subtractBalance')->willReturn(true);

        $createTransferService = new CreateTransferService(
            $this->mockTransactionRepo,
            $this->mockUserRepo
        );

        /** @var CreateTransferServiceInterface*/
        $transaction = $createTransferService->handleCreateTransfer($this->payee->id, $this->payer->id, 10);

        $this->assertEquals(10, $transaction->value);
        $this->assertEquals($this->payee->id, $transaction->payee_id);
        $this->assertEquals($this->payer->id, $transaction->payer_id);
    }

    /**
     * @test
     */
    public function test_should_return_transaction_model_when_finished()
    {
        /** @var CreateTransferService */
        $createTransferService = app(CreateTransferService::class);
        $transaction = $createTransferService->handleCreateTransfer($this->payee->id, $this->payer->id, 10);
        $this->assertInstanceOf(Transaction::class, $transaction);
    }
}
