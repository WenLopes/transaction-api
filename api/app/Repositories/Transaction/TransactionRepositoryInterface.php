<?php 

namespace App\Repositories\Transaction;

use App\Models\Transaction\Transaction;
use App\Repositories\BaseRepositoryInterface;

interface TransactionRepositoryInterface extends BaseRepositoryInterface {

    public function setAsComplete( int $transactionId ) : ?Transaction;

    public function setAsFailed( int $transactionId ) : ?Transaction;

}