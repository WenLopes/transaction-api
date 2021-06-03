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
    public function setAsSuccess( int $transactionId ) : bool 
    {
        return $this->update($transactionId, [
            'transaction_status_id' => config('constants.transaction.status.SUCCESS'),
            'processed_at' => now()
        ]);
    }

    /**
     * Set transaction status as error
     * @param int $transactionId
     * @return bool
     */
    public function setAsError( int $transactionId ) : bool 
    {
        return $this->update($transactionId, [
            'transaction_status_id' => config('constants.transaction.status.ERROR'),
            'processed_at' => now()
        ]);
    }
}