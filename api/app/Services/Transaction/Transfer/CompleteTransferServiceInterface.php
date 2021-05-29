<?php 

namespace App\Services\Transaction\Transfer;

use App\Models\Transaction\Transaction;

interface CompleteTransferServiceInterface {

    /**
     * Handle transfer completion
     * @return Transaction
     */
    public function handle(Transaction $transaction) : bool;
}