<?php 

namespace App\Services\Transaction\Transfer;

use App\Exceptions\Transaction\Transfer\CreateTransferException;
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

            throw new \Exception('Forçando erro');
            \DB::beginTransaction();
            \DB::commit();
            return true;

        } catch (\Exception $e) {

            \DB::rollback();
            throw new CreateTransferException($e->getMessage());

        }
    }
}