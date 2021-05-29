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
     * Adds value to the user's balance
     * @param int $transactionId
     * @return bool
     */
    public function setAsComplete( int $transactionId ) : bool {

        $transaction = $this->findById($transactionId);

        if( !$transaction ){
            return false;
        }

        $successStatus = config('constants.transaction.status.SUCCESS');

        return $this->update($transactionId, [
            'transaction_status_id' => $successStatus
        ]);
    }
}