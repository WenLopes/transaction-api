<?php 

namespace App\Repositories\Transaction;

use App\Repositories\BaseRepositoryInterface;

interface TransactionRepositoryInterface extends BaseRepositoryInterface {

    public function setAsComplete( int $transactionId ) : bool;

    public function setAsFailed( int $transactionId ) : bool;

}