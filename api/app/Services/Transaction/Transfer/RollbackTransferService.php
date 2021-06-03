<?php 

namespace App\Services\Transaction\Transfer;

use App\Events\Transaction\Transfer\TransferFailed;
use App\Exceptions\Transaction\Transfer\RollbackTransferException;
use App\Models\Transaction\Transaction;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use DB;
use Exception;

final class RollbackTransferService implements RollbackTransferServiceInterface {

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

    public function handleRollbackTransfer(Transaction $transaction) : bool
    {
        try {

            DB::beginTransaction();

            if( $transaction->alreadyProcessed() ) {
                throw new Exception("The transaction has already been processed previously");
            }

            if( ! $this->transactionRepo->setAsError($transaction->id) ){
                throw new Exception("Error setting transaction status as failed");
            }

            if( ! $this->userRepo->addBalance($transaction->payer_id, $transaction->value) ){
                throw new Exception("Error adding value to payer balance");
            }

            event( new TransferFailed($transaction->fresh()) );

            DB::commit();

            return true;

        } catch (\Exception $e){
            
            DB::rollback();
            throw new RollbackTransferException($e->getMessage());

        }
    }

}