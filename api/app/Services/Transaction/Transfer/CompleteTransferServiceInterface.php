<?php 

namespace App\Services\Transaction\Transfer;

interface CompleteTransferServiceInterface {

    /**
     * Handle transfer completion
     * @return bool
     */
    public function handle(int $transactionId) : bool;
}