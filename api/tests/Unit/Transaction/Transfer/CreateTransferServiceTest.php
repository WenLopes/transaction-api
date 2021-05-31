<?php

namespace Tests\Unit\Transaction\Transfer;

use App\Exceptions\Transaction\Transfer\CreateTransferException;
use App\Models\Transaction\Transaction;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Transaction\Transfer\CreateTransferService;
use App\Services\Transaction\Transfer\CreateTransferServiceInterface;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
    public function testShouldNotFinishWhenTransactionIsNotCreatedInDatabase()
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
    public function testShouldNotFinishWhenSubtractUserBalanceFail()
    {
        $this->expectException(CreateTransferException::class);
        $this->expectExceptionMessage('The user has no balance to proceed');

        $transactionFake = Transaction::create([
            'payee_id' => $this->payee->id,
            'payer_id' => $this->payer->id,
            'value' => 10
        ]);

        $this->transactionRepo->method('create')->willReturn($transactionFake);
        $this->userRepo->method('subtractBalance')->willReturn(false);

        $createTransferService = new CreateTransferService(
            $this->transactionRepo,
            $this->userRepo
        );

        /** @var CreateTransferServiceInterface*/
        $createTransferService->handle($this->payee->id, $this->payer->id, 10);
    }
}
