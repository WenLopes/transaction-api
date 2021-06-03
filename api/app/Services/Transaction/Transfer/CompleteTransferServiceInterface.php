<?php

namespace App\Services\Transaction\Transfer;

use App\Models\Transaction\Transaction;

interface CompleteTransferServiceInterface
{

    /**
     * Handle transfer completion
     * @return bool
     */
    public function handleCompleteTransfer(Transaction $transaction): bool;
}
