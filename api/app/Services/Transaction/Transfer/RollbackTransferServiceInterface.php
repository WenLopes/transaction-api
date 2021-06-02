<?php 

namespace App\Services\Transaction\Transfer;

use App\Models\Transaction\Transaction;

interface RollbackTransferServiceInterface {

    /**
     * Handle transfer rollback
     * @return bool
     */
    public function handle(Transaction $transaction) : bool;
}