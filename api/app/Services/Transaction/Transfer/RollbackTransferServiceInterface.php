<?php 

namespace App\Services\Transaction\Transfer;

interface RollbackTransferServiceInterface {

    /**
     * Handle transfer rollback
     * @return bool
     */
    public function handle(int $transactionId) : bool;
}