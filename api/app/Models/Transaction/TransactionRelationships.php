<?php 

namespace App\Models\Transaction;

trait TransactionRelationships {

    public function payer()
    {
        return $this->hasOne(\App\Models\User\User::class, 'id', 'payer_id');
    }

    public function payee()
    {
        return $this->hasOne(\App\Models\User\User::class, 'id', 'payee_id');
    }

    public function status()
    {
        return $this->hasOne(\App\Models\TransactionStatus\TransactionStatus::class, 'id', 'transaction_status_id');
    }
}