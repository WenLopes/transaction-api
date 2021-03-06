<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction\Transaction;
use App\Repositories\BaseRepositoryInterface;

interface TransactionRepositoryInterface extends BaseRepositoryInterface
{

    public function setAsSuccess(int $transactionId): bool;

    public function setAsError(int $transactionId): bool;
}
