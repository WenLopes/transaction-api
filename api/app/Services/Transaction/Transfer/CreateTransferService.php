<?php 

namespace App\Services\Transaction\Transfer;

use App\Exceptions\Transaction\Transfer\CreateTransferException;
use App\Models\Transaction\Transaction;
use App\Repositories\Transaction\TransactionRepositoryInterface;

class CreateTransferService implements CreateTransferServiceInterface {

    /** @var TransactionRepositoryInterface */
    protected $transactionRepo;

    public function __construct( TransactionRepositoryInterface $transactionRepo) {
        $this->transactionRepo = $transactionRepo;
    }

    public function handle(int $payeeId, int $payerId, float $value) : Transaction
    {
        try {
            \DB::beginTransaction();
            $transaction = $this->createTransaction($payeeId, $payerId, $value);
            \DB::commit();
            return $transaction;

        } catch (\Exception $e) {

            \DB::rollback();
            throw new CreateTransferException($e->getMessage());

        }
    }

    protected function createTransaction(int $payee, int $payer, float $value) : Transaction
    {
        return $this->transactionRepo->create([
            'payee_id' => $payee,
            'payer_id' => $payer,
            'value' => $value
        ]);
    }
}