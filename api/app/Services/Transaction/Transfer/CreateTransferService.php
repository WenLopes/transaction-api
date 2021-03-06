<?php

namespace App\Services\Transaction\Transfer;

use App\Exceptions\Transaction\Transfer\CreateTransferException;
use App\Jobs\Transaction\Transfer\ProcessTransferJob;
use App\Models\Transaction\Transaction;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use DB;
use Exception;

final class CreateTransferService implements CreateTransferServiceInterface
{

    /** @var TransactionRepositoryInterface */
    protected $transactionRepo;

    /** @var UserRepositoryInterface */
    protected $userRepo;

    public function __construct(
        TransactionRepositoryInterface $transactionRepo,
        UserRepositoryInterface $userRepo
    ) {
        $this->transactionRepo = $transactionRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Handle service
     * @var int $payee
     * @var int $payer
     * @var float $value
     * @return Transaction
    */
    public function handleCreateTransfer(int $payeeId, int $payerId, float $value): Transaction
    {
        try {
            DB::beginTransaction();

            $transaction = $this->createTransaction($payeeId, $payerId, $value);
            if (!$transaction) {
                throw new Exception("An error occurred while inserting transfer data on database");
            }

            if ($transaction->payer->is_seller) {
                throw new Exception("The payer cannot be a seller");
            }

            if (! $this->userRepo->subtractBalance($transaction->payer_id, $transaction->value)) {
                throw new Exception("The user has no balance to proceed");
            }

            dispatch(new ProcessTransferJob($transaction));

            DB::commit();

            return $transaction;
        } catch (\Exception $e) {
            DB::rollback();
            throw new CreateTransferException($e->getMessage());
        }
    }

    /**
     * Create transaction
     * @var int $payee
     * @var int $payer
     * @var float $value
     * @return Transaction
     */
    private function createTransaction(int $payee, int $payer, float $value): ?Transaction
    {
        return $this->transactionRepo->create([
            'payee_id' => $payee,
            'payer_id' => $payer,
            'value' => $value
        ]);
    }
}
