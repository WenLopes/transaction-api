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
    public function setAsComplete( int $transactionId ) : ?Transaction {

        $transaction = $this->findById($transactionId);

        if( !$transaction ){
            return false;
        }

        $successStatus = config('constants.transaction.status.SUCCESS');

        $query = $this->update($transactionId, [
            'transaction_status_id' => $successStatus
        ]);

        if( !$query ){
            return false;
        }

        return $this->findById($transactionId);
    }

    /**
     * Set transaction status as error
     * @param int $transactionId
     * @return bool
     */
    public function setAsFailed( int $transactionId ) : ?Transaction {

        $transaction = $this->findById($transactionId);

        if( !$transaction ){
            return false;
        }

        $errorStatus = config('constants.transaction.status.ERROR');

        $query = $this->update($transactionId, [
            'transaction_status_id' => $errorStatus
        ]);

        if( !$query ){
            return false;
        }

        return $this->findById($transactionId);
    }
}