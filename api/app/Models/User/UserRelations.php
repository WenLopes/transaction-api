<?php

namespace App\Models\User;

trait UserRelations
{

    public function paidTransactions()
    {
        return $this->hasMany(\App\Models\Transaction\Transaction::class, 'payer_id', 'id');
    }

    public function receivedTransactions()
    {
        return $this->hasMany(\App\Models\Transaction\Transaction::class, 'payee_id', 'id');
    }
}
