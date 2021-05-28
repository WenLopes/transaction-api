<?php 

namespace App\Services\Transaction\Transfer;

use App\Repositories\Transaction\TransactionRepositoryInterface;

class CreateTransferService {

    /** @var TransactionRepositoryInterface */
    protected $transactionRepo;

    public function __construct( TransactionRepositoryInterface $transactionRepo) {
        $this->transactionRepo = $transactionRepo;
    }

    public function handle(int $payeeId, int $payerId, float $value) : bool
    {
        try {
            \DB::beginTransaction();
            /* Logic here */
            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}