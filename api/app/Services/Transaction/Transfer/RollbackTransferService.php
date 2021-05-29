<?php 

namespace App\Services\Transaction\Transfer;

use App\Exceptions\Transaction\Transfer\RollbackTransferException;
use App\Models\Transaction\Transaction;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class RollbackTransferService implements RollbackTransferServiceInterface {

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

    public function handle(Transaction $transaction) : bool
    {
        try {

            \DB::beginTransaction();
            /** Code here */
            \DB::commit();

            return true;

        } catch (\Exception $e){
            
            \DB::rollback();
            throw new RollbackTransferException($e->getMessage());

        }
    }

}