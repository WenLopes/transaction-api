<?php 

namespace App\Services\Transaction\Transfer;

use App\Events\Transaction\Transfer\TransferFailed;
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

            if( ! $this->userRepo->addBalance($transaction->payer_id, $transaction->value) ){
                throw new \Exception("Error adding value to payer balance");
            }

            if( ! $this->transactionRepo->setAsFailed($transaction->id) ){
                throw new \Exception("Error setting transaction status as failed");
            }

            event( new TransferFailed($transaction) );

            \DB::commit();

            return true;

        } catch (\Exception $e){
            
            \DB::rollback();
            throw new RollbackTransferException($e->getMessage());

        }
    }

}