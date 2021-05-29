<?php 

namespace App\Services\Transaction\Transfer;

use App\Models\Transaction\Transaction;

interface CreateTransferServiceInterface {

    /**
     * Handle transfer creation
     * @return Transaction
     */
    public function handle(int $payee, int $payer, float $value) : Transaction;
}