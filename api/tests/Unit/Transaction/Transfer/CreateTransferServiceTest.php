<?php

namespace Tests\Unit\Transaction\Transfer;

use App\Exceptions\Transaction\Transfer\CreateTransferException;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Transaction\Transfer\CreateTransferService;
use App\Services\Transaction\Transfer\CreateTransferServiceInterface;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateTransferServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function serviceParametersDataProvider()
    {
        return [
            'valid_parameters' => [
                'payer' => 1,
                'payee' => 2,
                'value' => 10.00
            ]
        ];
    }

    /** 
     * @test 
     * @dataProvider serviceParametersDataProvider
     */
    public function testShouldNotFinishWhenTransactionIsNotCreatedInDatabase($payer, $payee, $value)
    {
        $this->expectException(CreateTransferException::class);

        $userRepo = $this->createMock(UserRepositoryInterface::class);

        $transactionRepo = $this->createMock(TransactionRepositoryInterface::class);
        $transactionRepo->method('create')->willReturn(null);

        $createTransferService = new CreateTransferService(
            $transactionRepo,
            $userRepo
        );

        /** @var CreateTransferServiceInterface*/
        $createTransferService->handle($payer, $payee, $value);
    }

    /** 
     * @test 
     * @dataProvider serviceParametersDataProvider
     */
    public function testShouldNotFinishWhenSubtractUserBalanceFail($payer, $payee, $value)
    {
        $this->expectException(CreateTransferException::class);

        $userRepo = $this->createMock(UserRepositoryInterface::class);
        $userRepo->method('subtractBalance')->willReturn(false);

        $transactionRepo = $this->createMock(TransactionRepositoryInterface::class);

        $createTransferService = new CreateTransferService(
            $transactionRepo,
            $userRepo
        );

        /** @var CreateTransferServiceInterface*/
        $createTransferService->handle($payer, $payee, $value);
    }
}
