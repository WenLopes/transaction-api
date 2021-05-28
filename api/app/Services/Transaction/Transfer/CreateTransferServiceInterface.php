<?php 

namespace App\Services\Transaction\Transfer;

interface CreateTransferServiceInterface {

    /**
     * Handle transfer creation
     * @return Transaction
     */
    public function handle(int $payee, int $payer, float $value) : \App\Models\Transaction\Transaction;
}