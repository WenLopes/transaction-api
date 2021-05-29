<?php 

namespace App\Services\Transaction\Transfer;

use App\Exceptions\Transaction\Transfer\CompleteTransferException;
use App\Models\Transaction\Transaction;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class CompleteTransferService implements CompleteTransferServiceInterface {

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

            if( ! $this->userRepo->addBalance($transaction->payee_id, $transaction->value) ){
                throw new \Exception("Error adding value to user balance");
            }

            if( ! $this->transactionRepo->setAsComplete($transaction->id) ){
                throw new \Exception("Error setting transaction status as complete");
            }

            \DB::commit();

            return true;

        } catch (\Exception $e){
            
            \DB::rollback();
            throw new CompleteTransferException($e->getMessage());

        }
    }

}