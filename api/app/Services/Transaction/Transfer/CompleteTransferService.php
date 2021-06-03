<?php 

namespace App\Services\Transaction\Transfer;

use App\Events\Transaction\Transfer\TransferSuccess;
use App\Exceptions\Transaction\Transfer\CompleteTransferException;
use App\Models\Transaction\Transaction;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use DB;
use Exception;

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

    public function handleCompleteTransfer(Transaction $transaction) : bool
    {
        try {

            DB::beginTransaction();

            if( $transaction->alreadyProcessed() ) {
                throw new Exception("The transaction has already been processed previously");
            }

            if( ! $this->transactionRepo->setAsSuccess($transaction->id) ){
                throw new Exception("Error setting transaction status as complete");
            }

            if( ! $this->userRepo->addBalance($transaction->payee_id, $transaction->value) ){
                throw new Exception("Error adding value to payee balance");
            }

            event( new TransferSuccess($transaction->fresh()) );

            DB::commit();

            return true;

        } catch (\Exception $e){
            
            DB::rollback();
            throw new CompleteTransferException($e->getMessage());

        }
    }

}