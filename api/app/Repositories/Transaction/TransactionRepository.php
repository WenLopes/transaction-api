<?php 

namespace App\Repositories\Transaction;

use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface {

    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    /**
     * Set transaction status as success
     * @param int $transactionId
     * @return bool
     */
    public function setAsSuccess( int $transactionId ) : bool {

        $transaction = $this->findById($transactionId);

        if( !$transaction ){
            return false;
        }

        $successStatus = config('constants.transaction.status.SUCCESS');

        if( $transaction->transaction_status_id == $successStatus ){
            return false;
        }

        return $this->update($transactionId, [
            'transaction_status_id' => $successStatus
        ]);

    }

    /**
     * Set transaction status as error
     * @param int $transactionId
     * @return bool
     */
    public function setAsError( int $transactionId ) : bool {

        $transaction = $this->findById($transactionId);

        if( !$transaction ){
            return false;
        }

        $errorStatus = config('constants.transaction.status.ERROR');

        if( $transaction->transaction_status_id == $errorStatus ){
            return false;
        }

        return $this->update($transactionId, [
            'transaction_status_id' => $errorStatus
        ]);
    }
}